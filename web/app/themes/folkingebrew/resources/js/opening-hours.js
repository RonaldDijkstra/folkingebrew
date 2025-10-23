export default function openingHours() {
  const today = new Date().getDay();
  const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  const currentDayName = dayNames[today];
  const tables = document.querySelectorAll('[data-opening-hours-table]');

  console.log('Found tables:', tables.length);

  tables.forEach(table => {
    // Skip if already processed
    if (table.dataset.processed === 'true') return;
    table.dataset.processed = 'true';

    const rows = table.querySelectorAll('tbody tr[data-day]');

    rows.forEach(row => {
      const dayAttribute = row.getAttribute('data-day');

      if (dayAttribute === currentDayName) {
        row.classList.add('font-bold', 'bg-neutral-light-brown');
      }
    });
  });
}
