// ecb2_admin.js - v1.1 - 11Jul17
//
//    - v1.1 - 11Jul17 - updated for max_number & required_number & updateECB2Placeholder()
//    - v1.0 - 18Apr17 - initial js file
//
//    enables drag-n-drop selection of list items, requires jquery ui sortable
//
//**************************************************************************************************
$(function() {

    $('ul.sortable-ecb2-list').each(function() {
        var $parent = $(this).closest('.ecb2-cb');
        var $selected = $parent.find('ul.selected-items');
        $(this).sortable({
            connectWith: $selected,
            delay: 150,
            revert: 300,
            placeholder: 'ui-state-highlight',
            items: 'li:not(.no-sort)',
            helper: function (event, ui) {
                if (!ui.hasClass('selected')) {
                    ui.addClass('selected')
                      .siblings()
                      .removeClass('selected');
                }
                var elements = ui.parent()
                                 .children('.selected')
                                 .clone(),
                    helper = $('<li/>');
                ui.data('multidrag', elements).siblings('.selected').remove();
                return helper.append(elements);
            },
            stop: function (event, ui) {
                var elements = ui.item.data('multidrag');
                var $ulSelected = $(ui.item).parent();
                ui.item.after(elements).remove();
                updateECB2CBInput($ulSelected);
            },
            receive: function(event, ui) {
                var elements = ui.item.data('multidrag');
                if ($(this).data('max-number') && $(this).children().length-1 > $(this).data('max-number')) {
                    $(ui.sender).sortable('cancel');
                } else {
                    updateECB2Placeholder( $(this) );
                    $(elements).removeClass('selected ui-state-hover')
                               .find('.sortable-remove').removeClass('hidden');
                }
            }
        });
    });

    // remove from selected list - by dragging back to available
    $('ul.selected-items').each(function() {
        var $parent = $(this).closest('.ecb2-cb');
        var $available = $parent.find('ul.available-items');
        $(this).sortable({
            connectWith: $available,
            delay: 150,
            revert: 300,
            placeholder: 'ui-state-highlight',
            stop: function (event, ui) {
                var $ulSelected = $(ui.item).closest('.ecb2-cb').find('.selected-items');
                $(ui.item).removeClass('selected')
                $(ui.item).children('.sortable-remove').addClass('hidden');
                updateECB2CBInput($ulSelected);
                updateECB2Placeholder($ulSelected);
            }
        });
    });

    // remove from selected list - by clicking remove icon
    $(document).on('click', '#selected-items .sortable-remove', function(e) {
        e.preventDefault();
        var $ulSelected = $(this).closest('ul.selected-items');
        var $ulAvailable = $(this).closest('.ecb2-cb').find('.available-items');
        $(this).addClass('hidden')
               .parent('li').removeClass('no-sort')
               .appendTo($ulAvailable);
        updateECB2CBInput($ulSelected);
        updateECB2Placeholder($ulSelected);
    });

    function updateECB2CBInput($ulSelected) {
        var $allSelected = $ulSelected.children('li:not(.placeholder)');
        var $targetInput = $( '#' + $ulSelected.data('cmsms-cb-input') );
        var selectedStr = '';
        var requiredNumber = $ulSelected.data('required-number');
        if ( requiredNumber && $allSelected.length!=requiredNumber ) {
            $targetInput.val('');   // set to empty

        } else {
            $allSelected.each(function() {
                if (selectedStr=='') {
                    selectedStr = $(this).data('cmsms-item-id');
                } else {
                    selectedStr = selectedStr+','+$(this).data('cmsms-item-id');
                }
            });
            $targetInput.val(selectedStr);
        }
    }

    function updateECB2Placeholder($ulSelected) {
        var requiredNumber = $ulSelected.data('required-number');
        var numberSelected = $ulSelected.children().length - 1; // exclude placeholder
        if ( (!requiredNumber && numberSelected>0) || (requiredNumber>0 && numberSelected==requiredNumber) ) {
            $ulSelected.children('.placeholder').addClass('hidden');
        } else {
            $ulSelected.children('.placeholder').removeClass('hidden');
        }


    }

});