(function($) {
    let selfName = 'neykaneViralListPosts';
    // add a reference to the JavaScript object we exposed via WordPress
    let wpJsObjectName = '_neykaneViralListPosts';
    window[selfName] = {
        // make it easier to rename dom classes, ids, and input names in the future
        getTextDomain: window[wpJsObjectName].text_domain,
        getIdPrefix: window[wpJsObjectName].id_prefix,
        getNamePrefix: '_' + window[wpJsObjectName].id_prefix,
        getItemsCount: function() {
            return +$('.' + window[selfName].getTextDomain + '-count').
                eq(0).
                val();
        },
        setItemsCount: function(count) {
            return $('.' + window[selfName].getTextDomain + '-count').
                eq(0).
                val(count);
        },
        getItemType: function(element) {
            return $(element).data('type');
        },
        getItemIndex: function(element) {
            return $(element).data('index');
        },
        getItemData: function(element) {
            let itemType = window[selfName].getItemType(element);
            let data = [];
            switch (itemType) {
                case 'slide':
                    // force tinyMCE editors to update their corresponding textarea values
                    tinyMCE.triggerSave();
                    data.push({
                        'findString': '*',
                        'filterString': 'img[class^="' +
                            window[selfName].getTextDomain +
                            '__item-body--left-img"]',
                        'valueType': 'attr',
                        'attrType': 'src',
                        'value': $(element).
                            find('*').
                            filter('img[class^="' +
                                window[selfName].getTextDomain +
                                '__item-body--left-img"]').
                            attr('src'),
                    });
                    data.push({
                        'findString': '*',
                        'filterString': 'input[name$="_image_url"]',
                        'valueType': 'val',
                        'value': $(element).
                            find('*').
                            filter('input[name$="_image_url"]').
                            val(),
                    });
                    data.push({
                        'findString': '*',
                        'filterString': 'input[name$="_title"]',
                        'valueType': 'val',
                        'value': $(element).
                            find('*').
                            filter('input[name$="_title"]').
                            val(),
                    });
                    data.push({
                        'findString': '*',
                        'filterString': 'textarea',
                        'valueType': 'val',
                        'value': $(element).find('*').filter('textarea').val(),
                    });
                    return data;
            }
        },
        setItemData: function(elements, data) {
            let items = $(elements).length;
            for (let i = 0; i < items; i++) {
                let item = elements.eq(i);
                let itemType = window[selfName].getItemType(item);
                switch (itemType) {
                    case 'slide':
                        data[i].forEach(function(datum) {
                            if (datum.valueType === 'val') {
                                $(item).
                                    find(datum.findString).
                                    filter(datum.filterString).
                                    val(datum.value);
                            } else if (datum.valueType === 'attr') {
                                $(item).
                                    find(datum.findString).
                                    filter(datum.filterString).
                                    attr(datum.attrType, datum.value);
                            }
                        });
                }
            }
        },
        removeDomElements: function(elements) {
            if (!Array.isArray(elements)) {
                elements = [elements];
            }
            elements.forEach(function(element) {
                $(element).remove();
            });
        },
        reorganizeItems: function(action, indexes, callback) {
            let oldIndex,
                newIndex;
            if (indexes) {
                oldIndex = indexes.oldIndex;
                newIndex = indexes.newIndex;
            }
            // if oldIndex and newIndex are the same, then the item was dragged back to its original position and we
            // don't have to do anything except refresh the editor
            if (oldIndex === newIndex) {
                window[selfName].refreshMceEditors(oldIndex);
                return;
            }
            if (action === 'delete') {
                newIndex = window[selfName].getItemsCount() + 1;
            }
            if (action === 'add' || action === 'duplicate') {
                oldIndex = newIndex;
                newIndex = window[selfName].getItemsCount() + 1;
            }
            // we'll store the integer index and type of all items that have to be rerendered
            let items = [];
            // we'll also store the existing dom elements that correspond to those indexes
            let itemElements = [];
            // and we'll store the data from the current dom elements so we can later restore them into the new dom
            // elements
            let itemsData = [];
            callback = callback || function() {
            };
            // now we'll begin looping at oldIndex, which is the first affected item, and end at newIndex, which is the
            // last affected item. during each iteration, we'll store the new index, the current dom element, and the
            // data from the current dom element.
            // a note on the ternary conditionals: if newIndex is less than oldIndex, it means an item was moved up
            // instead of down, which we can accommodate by reversing the logic of the loop.
            for (let i = oldIndex; oldIndex < newIndex ?
                i <= newIndex :
                i >= newIndex; oldIndex < newIndex ? i++ : i--) {
                let item;
                // if i is less than newIndex then we know that this item will be positioned 1 spot below what it used
                // to be, which means that we need to fetch the dom element that is at ( i + 1 ) so we can store its
                // current data in our itemsData array
                if (oldIndex < newIndex ? i < newIndex : i > newIndex) {
                    if (action === 'add' || action === 'duplicate') {
                        if (i === oldIndex) {
                            item = window[selfName].getItemContainerDomNode(i);
                        } else {
                            item = window[selfName].getItemContainerDomNode(
                                i - 1);
                        }
                    } else {
                        item = window[selfName].getItemContainerDomNode(
                            oldIndex < newIndex
                                ? i + 1
                                : i - 1,
                        );
                    }
                    // the last iteration covers a set of special cases
                } else if (i === newIndex) {
                    if (action === 'delete') {
                        window[selfName].removeDomElements(
                            window[selfName].getItemContainerDomNode(oldIndex));
                    } else if (action === 'add' || action === 'duplicate') {
                        item = window[selfName].getItemContainerDomNode(i - 1);
                    } else {
                        item = window[selfName].getItemContainerDomNode(
                            oldIndex);
                    }
                }
                // we'll store the index and type of this item, which we'll later use to create new items
                items.push({
                    'index': i,
                    'type': window[selfName].getItemType(item),
                });
                // we'll also store the current dom element corresponding to this item (we'll later delete these when
                // we're ready to place the newly rendered and reorganized items)
                itemElements.push(item);
                // finally, we'll store the data from this item so we can later restore it into the newly rendered
                // element
                itemsData.push(window[selfName].getItemData(item));
            }
            // in cases where an item was dragged up instead of down, and its oldIndex is greater than its newIndex,
            // we'll also want to split the order of our reference arrays so that the new dom elements that we render
            // via ajax will be in the correct order
            if (oldIndex > newIndex) {
                items.reverse();
                itemElements.reverse();
                itemsData.reverse();
            }
            // if we're in add mode, then lets ensure the newly-created site is blank
            if (action === 'add') {
                itemsData[0] = [];
            }
            // finally, we'll make an ajax call to render our new items. the php template will set the item index data
            // and select the correct template based on the item type
            window[selfName].renderNewItems(items, function(response) {
                // once we receive the rendered elements, we'll remove their previous dom representations
                window[selfName].removeDomElements(itemElements);
                // then, we'll update these dom elements with their corresponding data (copied from their previous dom
                // representations)
                window[selfName].setItemData(response, itemsData);
                // next, we'll insert these dom elements into our item container at the correct index
                window[selfName].insertItemIntoItemContainerAtPosition(response,
                    oldIndex < newIndex ? oldIndex : newIndex);
                // finally, we'll refresh the editors
                window[selfName].refreshMceEditors(items);
                // and we'll run the callback, if one was specified
                callback(response);
            });

        },
        refreshMceEditors: function(items) {
            if (!Array.isArray(items)) {
                items = [items];
            }
            items.forEach(function(item) {
                let index = item;
                if (typeof (item) === 'object') {
                    index = item.index;
                }
                tinymce.execCommand('mceRemoveEditor', true,
                    '' + window[selfName].getIdPrefix + '_items_' + index +
                    '_html');
                tinymce.execCommand('mceAddEditor', true,
                    '' + window[selfName].getIdPrefix + '_items_' + index +
                    '_html');
            });
        },
        renderNewItems: function(items, callback) {
            if (!Array.isArray(items)) {
                items = [items];
            }
            let data = {
                'action': 'render_' + window[selfName].getIdPrefix + '_item',
                'items': items,
            };
            // fetch the item template via ajax
            jQuery.post(ajaxurl, data, function(response) {
            }).done(function(response) {
                callback($(response));
            });
        },
        insertItemIntoItemContainerAtPosition: function(items, position) {
            let previousItemPosition = position - 1;
            let domNode;
            if (previousItemPosition > 0) {
                domNode = window[selfName].getItemContainerDomNode(
                    previousItemPosition);
            } else {
                domNode = window[selfName].getAddNewItemDomNode();
            }
            $(domNode).after(items);
        },
        getWrapperClassName: function() {
            return window[selfName].getTextDomain + '-items';
        },
        getAddNewItemDomNode: function() {
            return $('.' + window[selfName].getTextDomain + '-add-new-item').
                eq(0).
                get();
        },
        getItemContainerDomNode: function(item) {
            let containerDomNodeClass = window[selfName].getTextDomain +
                '__item-container';
            if (typeof (item) === 'number') {
                return $('.' + containerDomNodeClass + '[data-index="' + item +
                    '"]').
                    eq(0).
                    get();
            } else {
                return $(item).
                    parents('div[ class= "' + containerDomNodeClass + '" ]').
                    eq(0).
                    get();
            }
        },
    };

    $(document).ready(function($) {

        // configure drag and drop on item containers
        const sortable = new Draggable.Sortable(document.querySelectorAll(
            '.' + window[selfName].getWrapperClassName()), {
            draggable: '.' + window[selfName].getTextDomain +
                '__item-container',
            handle: '.' + window[selfName].getTextDomain + '__item-handle',
            delay: 500,
        });

        // reorganize items once the drag and drop library fires a stop event
        sortable.on('sortable:stop', function(d) {
            // set a 1 milisecond timeout otherwise some residual drag and drop library dom elements will cause some
            // issues for us
            setTimeout(function() {
                window[selfName].reorganizeItems('move',
                    {'oldIndex': d.oldIndex + 1, 'newIndex': d.newIndex + 1});
            }, 1);
        });

        // handle the media upload buttons
        $(document).
            on('click',
                '.' + window[selfName].getTextDomain + '-media-upload-btn',
                function(e) {
                    e.preventDefault();
                    let itemContainer = window[selfName].getItemContainerDomNode(
                        this);
                    let itemNumber = +$(itemContainer).data('index');
                    let image = wp.media({
                        title: 'Upload Image',
                        multiple: false,
                    }).open().on('select', function() {
                        let uploaded_image = image.state().
                            get('selection').
                            first();
                        let image_url = uploaded_image.toJSON().url;
                        $(itemContainer).
                            find('input[name^="' +
                                window[selfName].getNamePrefix +
                                '_items_' + itemNumber + '_image_url' + '"]').
                            eq(0).
                            val(image_url);
                        $(itemContainer).
                            find('img[class^="' +
                                window[selfName].getTextDomain +
                                '__item-body--left-img' + '"]').
                            eq(0).
                            attr('src', image_url);
                    });
                });

        // handle the add new dom node button
        $(window[selfName].getAddNewItemDomNode()).click(function(e) {
            e.preventDefault();
            let that = $(this);
            let itemsCount = window[selfName].getItemsCount();
            let items = {
                'index': itemsCount + 1,
                'type': 'slide',
            };
            that.prop('disabled', true);
            window[selfName].renderNewItems(items, function(response) {
                itemsCount++;
                window[selfName].setItemsCount(itemsCount);
                window[selfName].insertItemIntoItemContainerAtPosition(response,
                    itemsCount);
                window[selfName].refreshMceEditors(items);
                that.prop('disabled', false);
            });
        });

        // handle the add here button in each item's toolbar
        $(document).
            on('click', '.' + window[selfName].getTextDomain +
                '__item-handle--add-button', function(e) {
                e.preventDefault();
                let that = $(this);
                that.prop('disabled', true);
                let itemsCount = window[selfName].getItemsCount();
                let oldIndex = window[selfName].getItemIndex(
                    window[selfName].getItemContainerDomNode(that));
                if (itemsCount === oldIndex) {
                    $(window[selfName].getAddNewItemDomNode()).click();
                    that.prop('disabled', false);
                } else {
                    window[selfName].reorganizeItems('add',
                        {'newIndex': oldIndex + 1}, function() {
                            window[selfName].setItemsCount(itemsCount + 1);
                            that.prop('disabled', false);
                        });
                }
            });

        // handle the duplicate button in each item's toolbar
        $(document).
            on('click', '.' + window[selfName].getTextDomain +
                '__item-handle--duplicate-button', function(e) {
                e.preventDefault();
                let that = $(this);
                that.prop('disabled', true);
                let itemsCount = window[selfName].getItemsCount();
                let oldIndex = window[selfName].getItemIndex(
                    window[selfName].getItemContainerDomNode(that));
                window[selfName].reorganizeItems('duplicate',
                    {'newIndex': oldIndex}, function() {
                        window[selfName].setItemsCount(itemsCount + 1);
                        that.prop('disabled', false);
                    });
            });

        // handle the move up button in each item's toolbar
        $(document).
            on('click', '.' + window[selfName].getTextDomain +
                '__item-handle--up-button', function(e) {
                e.preventDefault();
                let that = $(this);
                that.prop('disabled', true);
                let oldIndex = window[selfName].getItemIndex(
                    window[selfName].getItemContainerDomNode(that));
                if ((oldIndex - 1) > 0) {
                    window[selfName].reorganizeItems('move',
                        {'oldIndex': oldIndex, 'newIndex': oldIndex - 1},
                        function() {
                            that.prop('disabled', false);
                        });
                } else {
                    that.prop('disabled', false);
                }
            });

        // handle the move down button in each item's toolbar
        $(document).
            on('click', '.' + window[selfName].getTextDomain +
                '__item-handle--down-button', function(e) {
                e.preventDefault();
                let that = $(this);
                that.prop('disabled', true);
                let oldIndex = window[selfName].getItemIndex(
                    window[selfName].getItemContainerDomNode(that));
                let itemsCount = window[selfName].getItemsCount();
                if ((oldIndex + 1) <= itemsCount) {
                    window[selfName].reorganizeItems('move',
                        {'oldIndex': oldIndex, 'newIndex': oldIndex + 1},
                        function() {
                            that.prop('disabled', false);
                        });
                } else {
                    that.prop('disabled', false);
                }
            });

        // handle the delete button in each item's toolbar
        $(document).
            on('click', '.' + window[selfName].getTextDomain +
                '__item-handle--delete-button', function(e) {
                e.preventDefault();
                let that = $(this);
                // disable all delete buttons until this deletion is complete
                $('.' + window[selfName].getTextDomain +
                    '__item-handle--delete-button').each(function() {
                    $(this).prop('disabled', true);
                });
                let itemsCount = window[selfName].getItemsCount();
                let oldIndex = window[selfName].getItemIndex(
                    window[selfName].getItemContainerDomNode(that));
                window[selfName].reorganizeItems('delete',
                    {'oldIndex': oldIndex},
                    function() {
                        window[selfName].setItemsCount(itemsCount - 1);
                        $('.' + window[selfName].getTextDomain +
                            '__item-handle--delete-button').each(function() {
                            $(this).prop('disabled', false);
                        });
                    });
            });

    });
console.log(ajaxurl);
})(jQuery);
