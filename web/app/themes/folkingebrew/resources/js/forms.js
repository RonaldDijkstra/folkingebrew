export default function forms() {
  window.addEventListener('DOMContentLoaded', () => {
    initTimeFields();
    initMultipleChoiceFields();
    initRadioOtherFields();
    initListFields();
    initNumberFields();
  });
}

/**
 * Initialize time field functionality
 */
function initTimeFields() {
  const timeFields = document.querySelectorAll('.time-field input[type="number"]');

  timeFields.forEach(input => {
    const isHourField = input.name.includes('_1');
    const isMinuteField = input.name.includes('_2');

    if (isHourField) {
      initHourField(input);
    } else if (isMinuteField) {
      initMinuteField(input);
    }
  });
}

/**
 * Initialize hour field with proper validation and formatting
 * @param {HTMLInputElement} input - The hour input element
 */
function initHourField(input) {
  input.addEventListener('blur', function() {
    const value = parseInt(this.value);
    if (!isNaN(value)) {
      const is12Hour = this.max === '12';
      let formattedValue = value;

      if (is12Hour) {
        if (value < 1) formattedValue = 1;
        if (value > 12) formattedValue = 12;
        this.value = formattedValue.toString();
      } else {
        if (value < 0) formattedValue = 0;
        if (value > 23) formattedValue = 23;
        this.value = formattedValue.toString().padStart(2, '0');
      }
    }
  });
}

/**
 * Initialize minute field with proper validation and formatting
 * @param {HTMLInputElement} input - The minute input element
 */
function initMinuteField(input) {
  input.addEventListener('blur', function() {
    const value = parseInt(this.value);
    if (!isNaN(value)) {
      let formattedValue = value;
      if (value < 0) formattedValue = 0;
      if (value > 59) formattedValue = 59;
      this.value = formattedValue.toString().padStart(2, '0');
    }
  });
}

/**
 * Initialize multiple choice field functionality
 */
function initMultipleChoiceFields() {
  const multipleChoiceContainers = document.querySelectorAll('.multiple-choice-container');

  multipleChoiceContainers.forEach(container => {
    const selectMode = container.dataset.selectMode;
    const fieldId = container.dataset.fieldId;
    const formId = container.dataset.formId;
    const exactCount = parseInt(container.dataset.exactCount) || 1;
    const minRange = parseInt(container.dataset.minRange) || 0;
    const maxRange = parseInt(container.dataset.maxRange) || 0;
    const enableSelectAll = container.dataset.enableSelectAll === 'true';
    const enableOther = container.dataset.enableOther === 'true';

    // Initialize select all functionality
    if (enableSelectAll) {
      initSelectAllButton(container, selectMode);
    }

    // Initialize other choice functionality
    if (enableOther) {
      initOtherChoice(container);
    }

    // Initialize selection validation and counting
    initSelectionValidation(container, selectMode, exactCount, minRange, maxRange);
  });
}

/**
 * Initialize select all button functionality
 * @param {HTMLElement} container - The multiple choice container
 * @param {string} selectMode - The selection mode
 */
function initSelectAllButton(container, selectMode) {
  const selectAllBtn = container.querySelector('.select-all-btn');
  const checkboxInputs = container.querySelectorAll('input[type="checkbox"]:not(.other-choice-input)');

  if (!selectAllBtn || checkboxInputs.length === 0) return;

  selectAllBtn.addEventListener('click', function() {
    const allChecked = Array.from(checkboxInputs).every(input => input.checked);
    const newState = !allChecked;

    // Check if we need to validate selection limits
    if (newState && selectMode === 'exactly') {
      const exactCount = parseInt(container.dataset.exactCount);
      if (checkboxInputs.length !== exactCount) {
        showValidationMessage(container, 'exactly');
        return;
      }
    }

    if (newState && selectMode === 'range') {
      const maxRange = parseInt(container.dataset.maxRange);
      if (maxRange > 0 && checkboxInputs.length > maxRange) {
        showValidationMessage(container, 'range');
        return;
      }
    }

    checkboxInputs.forEach(input => {
      input.checked = newState;
      triggerChangeEvent(input);
    });

    // Update button text
    this.textContent = newState ? 'Deselect All' : (container.dataset.selectAllText || 'Select All');

    updateSelectionCounter(container);
    validateSelection(container, selectMode);
  });

  // Update button state when individual checkboxes change
  checkboxInputs.forEach(input => {
    input.addEventListener('change', function() {
      const allChecked = Array.from(checkboxInputs).every(input => input.checked);
      const noneChecked = Array.from(checkboxInputs).every(input => !input.checked);

      if (allChecked) {
        selectAllBtn.textContent = 'Deselect All';
      } else if (noneChecked) {
        selectAllBtn.textContent = container.dataset.selectAllText || 'Select All';
      } else {
        selectAllBtn.textContent = container.dataset.selectAllText || 'Select All';
      }
    });
  });
}

/**
 * Initialize other choice functionality
 * @param {HTMLElement} container - The multiple choice container
 */
function initOtherChoice(container) {
  const otherChoiceInput = container.querySelector('.other-choice-input');
  const otherTextInput = container.querySelector('.other-text-input');

  if (!otherChoiceInput || !otherTextInput) return;

  // Enable/disable text input based on other choice selection
  otherChoiceInput.addEventListener('change', function() {
    const isChecked = this.checked;
    otherTextInput.disabled = !isChecked;
    otherTextInput.classList.toggle('opacity-50', !isChecked);

    if (isChecked) {
      otherTextInput.focus();
      // Set the value to trigger validation if needed
      if (!otherTextInput.value.trim()) {
        otherTextInput.value = 'Other';
      }
    } else {
      otherTextInput.value = '';
    }

    triggerChangeEvent(otherTextInput);
  });

  // Auto-check the other choice when text is entered
  otherTextInput.addEventListener('input', function() {
    if (this.value.trim() && !otherChoiceInput.checked) {
      otherChoiceInput.checked = true;
      otherChoiceInput.disabled = false;
      this.classList.remove('opacity-50');
      triggerChangeEvent(otherChoiceInput);
    }
  });

  // Clear other choice if text is empty
  otherTextInput.addEventListener('blur', function() {
    if (!this.value.trim() && otherChoiceInput.checked) {
      otherChoiceInput.checked = false;
      triggerChangeEvent(otherChoiceInput);
    }
  });
}

/**
 * Initialize selection validation and counting
 * @param {HTMLElement} container - The multiple choice container
 * @param {string} selectMode - The selection mode
 * @param {number} exactCount - Exact count for 'exactly' mode
 * @param {number} minRange - Minimum for 'range' mode
 * @param {number} maxRange - Maximum for 'range' mode
 */
function initSelectionValidation(container, selectMode, exactCount, minRange, maxRange) {
  const inputs = container.querySelectorAll('.choice-input');
  const counter = container.querySelector('.selection-counter');

  inputs.forEach(input => {
    input.addEventListener('change', function() {
      updateSelectionCounter(container);
    });
  });

  // Initial update
  updateSelectionCounter(container);
}

/**
 * Update the selection counter display
 * @param {HTMLElement} container - The multiple choice container
 */
function updateSelectionCounter(container) {
  const counter = container.querySelector('.selection-counter');
  const currentCountSpan = container.querySelector('.current-count');

  if (!counter || !currentCountSpan) return;

  const selectedCount = getSelectedCount(container);
  currentCountSpan.textContent = selectedCount;

  // Show/hide counter based on selection
  if (selectedCount > 0) {
    counter.style.display = 'block';
  } else {
    counter.style.display = 'none';
  }
}

/**
 * Get the current number of selected choices
 * @param {HTMLElement} container - The multiple choice container
 * @returns {number} - Number of selected choices
 */
function getSelectedCount(container) {
  const checkedInputs = container.querySelectorAll('.choice-input:checked');
  return checkedInputs.length;
}

/**
 * Validate the current selection based on mode
 * @param {HTMLElement} container - The multiple choice container
 * @param {string} selectMode - The selection mode
 */
function validateSelection(container, selectMode) {
  hideValidationMessages(container);

  const selectedCount = getSelectedCount(container);
  const exactCount = parseInt(container.dataset.exactCount);
  const minRange = parseInt(container.dataset.minRange);
  const maxRange = parseInt(container.dataset.maxRange);

  let isValid = true;

  if (selectMode === 'exactly' && selectedCount !== exactCount) {
    isValid = false;
    if (selectedCount > 0) { // Only show message if user has made selections
      showValidationMessage(container, 'exactly');
    }
  }

  if (selectMode === 'range') {
    if (minRange > 0 && selectedCount < minRange) {
      isValid = false;
      if (selectedCount > 0) { // Only show message if user has made selections
        showValidationMessage(container, 'range');
      }
    }
    if (maxRange > 0 && selectedCount > maxRange) {
      isValid = false;
      showValidationMessage(container, 'range');
    }
  }

  return isValid;
}

/**
 * Show validation message for the specified type
 * @param {HTMLElement} container - The multiple choice container
 * @param {string} type - The validation type ('exactly' or 'range')
 */
function showValidationMessage(container, type) {
  const message = container.querySelector(`.validation-message.${type}-message`);
  if (message) {
    message.style.display = 'block';
    setTimeout(() => {
      message.style.display = 'none';
    }, 3000); // Hide after 3 seconds
  }
}

/**
 * Hide all validation messages
 * @param {HTMLElement} container - The multiple choice container
 */
function hideValidationMessages(container) {
  const messages = container.querySelectorAll('.validation-message');
  messages.forEach(message => {
    message.style.display = 'none';
  });
}

/**
 * Initialize radio fields with "Other" choice functionality
 */
function initRadioOtherFields() {
  const radioContainers = document.querySelectorAll('.flex.flex-col.gap-1');

  radioContainers.forEach(container => {
    // Look for radio with value "gf_other_choice" or with other-choice-input class
    const otherRadio = container.querySelector('input[type="radio"][value="gf_other_choice"]') ||
                      container.querySelector('.other-choice-input[type="radio"]');
    const otherTextInput = container.querySelector('.other-text-input');
    const allRadios = container.querySelectorAll('input[type="radio"]');

    if (!otherRadio || !otherTextInput) return;

    // Enable/disable text input based on radio selection
    allRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const isOtherSelected = otherRadio.checked;
        otherTextInput.disabled = !isOtherSelected;
        otherTextInput.classList.toggle('opacity-50', !isOtherSelected);

        if (isOtherSelected) {
          otherTextInput.focus();
        } else {
          // Clear the text when other radio options are selected
          otherTextInput.value = '';
        }
      });
    });

    // Auto-select the other radio when text is entered
    otherTextInput.addEventListener('input', function() {
      if (this.value.trim() && !otherRadio.checked) {
        otherRadio.checked = true;
        this.disabled = false;
        this.classList.remove('opacity-50');
        triggerChangeEvent(otherRadio);
      }
    });

    // Initialize the state on page load
    const isOtherSelected = otherRadio.checked;
    otherTextInput.disabled = !isOtherSelected;
    otherTextInput.classList.toggle('opacity-50', !isOtherSelected);
  });
}

/**
 * Initialize list field functionality
 */
function initListFields() {
  const listContainers = document.querySelectorAll('.list-field-container');

  listContainers.forEach(container => {
    const fieldId = container.dataset.fieldId;
    const maxRows = parseInt(container.dataset.maxRows) || 0;
    const enableColumns = container.dataset.enableColumns === 'true';
    const columnCount = parseInt(container.dataset.columnCount) || 1;

    // Add event listeners for add/remove buttons
    container.addEventListener('click', function(e) {
      if (e.target.classList.contains('add-row-btn')) {
        addListRow(container, fieldId, columnCount, maxRows);
      } else if (e.target.classList.contains('remove-row-btn')) {
        removeListRow(e.target.closest('.list-row'), container);
      }
    });

    // Update row count on initialization
    updateRowCount(container);
  });
}

/**
 * Add a new row to the list field
 * @param {HTMLElement} container - The list field container
 * @param {string} fieldId - The field ID
 * @param {number} columnCount - Number of columns
 * @param {number} maxRows - Maximum allowed rows (0 = unlimited)
 */
function addListRow(container, fieldId, columnCount, maxRows) {
  const rowsContainer = container.querySelector('.list-rows');
  const currentRows = rowsContainer.querySelectorAll('.list-row');

  // Check max rows limit
  if (maxRows > 0 && currentRows.length >= maxRows) {
    alert(`Maximum ${maxRows} rows allowed.`);
    return;
  }

  // Get template
  const template = document.getElementById(`list-row-template-${fieldId}`);
  if (!template) return;

  // Clone template content
  const newRow = template.content.cloneNode(true).querySelector('.list-row');
  const newRowIndex = currentRows.length;

  // Update row index and input names/ids
  newRow.dataset.rowIndex = newRowIndex;

  const inputs = newRow.querySelectorAll('input[type="text"]');
  inputs.forEach((input, colIndex) => {
    const rowNumber = newRowIndex + 1;
    const colNumber = colIndex + 1;

    // Update name attribute - Gravity Forms uses array notation for all list inputs
    input.name = `input_${fieldId}[]`;

    // Update id attribute
    input.id = input.id.replace('__ROW_INDEX__', newRowIndex).replace('__COL_INDEX__', colIndex);
  });

  // Append new row
  rowsContainer.appendChild(newRow);

  // Focus first input in new row
  const firstInput = newRow.querySelector('input[type="text"]');
  if (firstInput) {
    firstInput.focus();
  }

  // Update row count
  updateRowCount(container);
}

/**
 * Remove a row from the list field
 * @param {HTMLElement} row - The row to remove
 * @param {HTMLElement} container - The list field container
 */
function removeListRow(row, container) {
  const rowsContainer = container.querySelector('.list-rows');
  const allRows = rowsContainer.querySelectorAll('.list-row');

  // Don't allow removing the last row
  if (allRows.length <= 1) {
    alert('At least one row is required.');
    return;
  }

  // Remove the row
  row.remove();

  // Reindex remaining rows
  reindexListRows(container);

  // Update row count
  updateRowCount(container);
}

/**
 * Reindex all rows after adding/removing rows
 * @param {HTMLElement} container - The list field container
 */
function reindexListRows(container) {
  const fieldId = container.dataset.fieldId;
  const columnCount = parseInt(container.dataset.columnCount) || 1;
  const rows = container.querySelectorAll('.list-row');

  rows.forEach((row, rowIndex) => {
    row.dataset.rowIndex = rowIndex;

    const inputs = row.querySelectorAll('input[type="text"]');
    inputs.forEach((input, colIndex) => {
      const rowNumber = rowIndex + 1;
      const colNumber = colIndex + 1;

      // Update name attribute - Gravity Forms uses array notation for all list inputs
      input.name = `input_${fieldId}[]`;

      // Update id attribute
      const formId = container.dataset.formId;
      input.id = `input_${formId}_${fieldId}_${rowIndex}_${colIndex}`;
    });
  });
}

/**
 * Update the row count display
 * @param {HTMLElement} container - The list field container
 */
function updateRowCount(container) {
  const rowCountSpan = container.querySelector('.current-row-count');
  if (!rowCountSpan) return;

  const currentRows = container.querySelectorAll('.list-row').length;
  rowCountSpan.textContent = currentRows;

  // Update add button states based on max rows
  const maxRows = parseInt(container.dataset.maxRows) || 0;
  const addButtons = container.querySelectorAll('.add-row-btn');

  addButtons.forEach(btn => {
    if (maxRows > 0 && currentRows >= maxRows) {
      btn.disabled = true;
      btn.classList.add('opacity-50', 'cursor-not-allowed');
      btn.classList.remove('hover:bg-green-600');
    } else {
      btn.disabled = false;
      btn.classList.remove('opacity-50', 'cursor-not-allowed');
      btn.classList.add('hover:bg-green-600');
    }
  });
}

/**
 * Trigger a change event on an element
 * @param {HTMLElement} element - The element to trigger the event on
 */
function triggerChangeEvent(element) {
  const event = new Event('change', { bubbles: true });
  element.dispatchEvent(event);
}

/**
 * Initialize number field functionality with increase/decrease buttons
 */
function initNumberFields() {
  // Use event delegation to handle dynamically generated fields
  document.addEventListener('click', (e) => {
    const target = e.target.closest('#increaseButton, #decreaseButton');
    if (!target) return;

    // Find the number input within the same parent container
    const container = target.closest('.relative');
    if (!container) return;

    const input = container.querySelector('input[type="number"]');
    if (!input) return;

    e.preventDefault();

    if (target.id === 'increaseButton') {
      // Increase the value and respect the max attribute
      const currentValue = parseInt(input.value || 0);
      const maxValue = input.max ? parseInt(input.max) : Infinity;
      input.value = Math.min(maxValue, currentValue + 1);
    } else if (target.id === 'decreaseButton') {
      // Decrease the value and respect the min attribute
      const currentValue = parseInt(input.value || 0);
      const minValue = input.min ? parseInt(input.min) : 0;
      input.value = Math.max(minValue, currentValue - 1);
    }

    // Trigger change event for any listeners
    triggerChangeEvent(input);
  });
}
