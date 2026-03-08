let index = 0;

function updateSlidePosition() {
    const slides = document.querySelector(".slides");
    slides.style.transform = `translateX(${-index * 100}%)`;
}

function changeSlide(step) {
    const slides = document.querySelectorAll(".slide");
    index = (index + step + slides.length) % slides.length; // Wrap around
    updateSlidePosition();
}

// Automatically change slides every 5 seconds
setInterval(() => changeSlide(1), 5000);

document.querySelectorAll('.faq-question').forEach(button => {
  button.addEventListener('click', () => {
    const answer = button.nextElementSibling;

    // Toggle the display of the answer
    if (answer.style.display === 'block') {
      answer.style.display = 'none';
    } else {
      answer.style.display = 'block';
    }
  });
}); 