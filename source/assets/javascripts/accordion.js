export default function accordion() {
  window.addEventListener('DOMContentLoaded', () => {
  	if (document.querySelector('.accordion')) {
  		const items = document.querySelectorAll(".accordion-button");

			function toggleAccordion() {
				console.log('tick');
			  const itemToggle = this.getAttribute('aria-expanded');
			  
			  for (var i = 0; i < items.length; i++) {
			    items[i].setAttribute('aria-expanded', 'false');
			  }
			  
			  if (itemToggle == 'false') {
			    this.setAttribute('aria-expanded', 'true');
			  }
			}

			items.forEach(item => item.addEventListener('click', toggleAccordion));
  	}

   	if (document.querySelector('.accordion')) {
	    document.querySelectorAll('.accordion-button').forEach((item) => {
	      item.addEventListener('click', (event) => {
	        event.preventDefault();

	        item.classList.toggle('active');
	        const next = item.nextSibling.nextSibling;
	        next.classList.toggle('hidden');
	        // animate
	      });
	    });
	  }
  });
}