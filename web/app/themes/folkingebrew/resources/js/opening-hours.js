export default function openingHours() {
  const today = new Date().getDay();
  
  const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
  const currentDayName = dayNames[today];
  const tables = document.querySelectorAll('[data-opening-hours-table]');
  
  tables.forEach(table => {
    const rows = table.querySelectorAll('tbody tr[data-day]');
    
    rows.forEach(row => {
      const dayAttribute = row.getAttribute('data-day');
      
      if (dayAttribute === currentDayName) {
        
        row.classList.add('font-bold', 'bg-neutral-light-brown');
      }
    });
  });
}
