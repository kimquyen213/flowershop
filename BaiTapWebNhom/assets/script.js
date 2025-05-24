const slides = document.querySelector('.slides');
  const slideCount = document.querySelectorAll('.slide').length;
  let currentIndex = 0;

  const updateSlidePosition = () => {
    slides.style.transform = `translateX(-${currentIndex * 100}%)`;
  };

  document.querySelector('.nav-arrow.next').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % slideCount;
    updateSlidePosition();
  });

  document.querySelector('.nav-arrow.prev').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + slideCount) % slideCount;
    updateSlidePosition();
  });

  // Optional: Tự động trượt mỗi 5s
  setInterval(() => {
    currentIndex = (currentIndex + 1) % slideCount;
    updateSlidePosition();
  }, 5000);