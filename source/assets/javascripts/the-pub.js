export default function thePub() {
  document.addEventListener('DOMContentLoaded', () => {
    // Map day of week number (0-6) to the index of the row in the table (0-4)
    const dayMapping = {
      0: 4,
      3: 0,
      4: 1,
      5: 2,
      6: 3
    };

    const currentDayOfWeek = new Date().getDay();

    if (Object.prototype.hasOwnProperty.call(dayMapping, currentDayOfWeek)) {
      // Use a more specific selector for the pub hours table
      const tableRows = document.querySelector('.pub-hours-table tbody').querySelectorAll('tr');

      const currentDayRow = tableRows[dayMapping[currentDayOfWeek]];

      currentDayRow.classList.add('font-bold');

      const dayCell = currentDayRow.querySelector('td:first-child');
      dayCell.innerHTML += ' <span class="text-green-600">(Today)</span>';
    }
  });
}
