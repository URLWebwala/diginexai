// Global function to initialize testimonial slider
function initTestimonialSlider() {
  const track = document.getElementById("t-sliderTrack");
  const prevBtn = document.getElementById("t-prevBtn");
  const nextBtn = document.getElementById("t-nextBtn");

  // Check if required elements exist FIRST
  if (!track || !prevBtn || !nextBtn) {
    console.warn("Testimonial slider: Required elements not found", {
      track: !!track,
      prevBtn: !!prevBtn,
      nextBtn: !!nextBtn
    });
    return;
  }

  // Get cards AFTER confirming track exists
  const originalCards = Array.from(
    track.querySelectorAll(".t-original-card")
  );
  const totalCards = originalCards.length;

  // Check if we have cards
  if (totalCards === 0) {
    console.warn("Testimonial slider: No testimonial cards found");
    return;
  }

  // Prevent duplicate initialization
  if (track.dataset.initialized === 'true') {
    console.log("Testimonial slider: Already initialized, skipping...");
    return;
  }

  // Clear any existing clones to avoid duplicates
  const existingClones = track.querySelectorAll(".t-clone");
  existingClones.forEach(clone => clone.remove());

  const cardsToClone = 2;
  
  // Only clone if we have enough cards
  if (totalCards >= cardsToClone) {
    // Clone last cards to prepend (for infinite scroll effect)
    for (let i = 1; i <= cardsToClone; i++) {
      const cardIndex = totalCards - i;
      // Double-check array bounds and element validity
      if (cardIndex >= 0 && cardIndex < originalCards.length && 
          originalCards[cardIndex] && 
          originalCards[cardIndex].nodeType === 1 && // Ensure it's an element node
          typeof originalCards[cardIndex].cloneNode === 'function') {
        try {
          const clone = originalCards[cardIndex].cloneNode(true);
          if (clone) {
            clone.classList.add("t-clone");
            track.prepend(clone);
          }
        } catch (error) {
          console.warn('Error cloning card at index', cardIndex, ':', error);
        }
      }
    }
    // Clone first cards to append (for infinite scroll effect)
    for (let i = 0; i < cardsToClone; i++) {
      // Double-check array bounds and element validity
      if (i < originalCards.length && 
          originalCards[i] && 
          originalCards[i].nodeType === 1 && // Ensure it's an element node
          typeof originalCards[i].cloneNode === 'function') {
        try {
          const clone = originalCards[i].cloneNode(true);
          if (clone) {
            clone.classList.add("t-clone");
            track.appendChild(clone);
          }
        } catch (error) {
          console.warn('Error cloning card at index', i, ':', error);
        }
      }
    }
  }

  const allCards = Array.from(track.children);
  let currentIndex = cardsToClone;
  
  // Get CSS variables with fallback values
  const cardWidthStr = getComputedStyle(document.documentElement).getPropertyValue("--card-width");
  const cardGapStr = getComputedStyle(document.documentElement).getPropertyValue("--card-gap");
  
  let cardWidth = parseFloat(cardWidthStr) || 380; // Default: 380px
  let cardGap = parseFloat(cardGapStr) || 20; // Default: 20px
  
  // If CSS variables are not set, try to get from first card
  if (isNaN(cardWidth) || cardWidth === 0) {
    const firstCard = allCards[0];
    if (firstCard) {
      const cardStyle = getComputedStyle(firstCard);
      cardWidth = parseFloat(cardStyle.width) || 380;
    }
  }
  
  if (isNaN(cardGap) || cardGap === 0) {
    cardGap = 20; // Default gap
  }
  
  const cardTotalWidth = cardWidth + cardGap;
  const AUTOSLIDE_DURATION = 2500;
  
  console.log('📐 Testimonial slider dimensions:', {
    cardWidth: cardWidth,
    cardGap: cardGap,
    cardTotalWidth: cardTotalWidth,
    totalCards: allCards.length
  });

  function updateSlider(animate = true) {
    if (!animate) {
      track.classList.add("no-transition");
    } else {
      track.classList.remove("no-transition");
    }

    const offset = currentIndex * cardTotalWidth;
    track.style.transform = `translateX(-${offset}px)`;

    allCards.forEach((card, index) => {
      const isActive = index === currentIndex;
      card.classList.toggle("active", isActive);

      if (!isActive && !card.classList.contains("t-clone")) {
        card.onclick = () => {
          currentIndex = index;
          updateSlider();
          resetAutoplay();
        };
      }
    });

    if (animate) {
      const transitionTime =
        parseFloat(getComputedStyle(track).transitionDuration) * 1000;

      if (currentIndex === allCards.length - cardsToClone) {
        setTimeout(() => {
          currentIndex = cardsToClone;
          updateSlider(false);
        }, transitionTime);
      } else if (currentIndex === cardsToClone - 1) {
        setTimeout(() => {
          currentIndex = allCards.length - (cardsToClone + 1);
          updateSlider(false);
        }, transitionTime);
      }
    }
  }

  function moveToNext() {
    currentIndex++;
    updateSlider();
  }

  function moveToPrev() {
    currentIndex--;
    updateSlider();
  }

  // Store autoplay interval reference on track element to allow cleanup
  if (track.autoplayInterval) {
    clearInterval(track.autoplayInterval);
  }

  // Remove existing event listeners by cloning buttons (clean approach)
  const nextBtnHandler = () => {
    moveToNext();
    resetAutoplay();
  };
  const prevBtnHandler = () => {
    moveToPrev();
    resetAutoplay();
  };

  // Remove old event listeners by cloning buttons (cleaner approach)
  // Store original button IDs - only if buttons exist
  const nextBtnId = nextBtn ? nextBtn.id : null;
  const prevBtnId = prevBtn ? prevBtn.id : null;
  
  // Clone and replace buttons to remove all event listeners
  if (nextBtn && nextBtn.parentNode && typeof nextBtn.cloneNode === 'function') {
    try {
      const newNextBtn = nextBtn.cloneNode(true);
      if (newNextBtn && nextBtnId) {
        newNextBtn.id = nextBtnId; // Ensure ID is preserved
      }
      if (nextBtn.parentNode) {
        nextBtn.parentNode.replaceChild(newNextBtn, nextBtn);
      }
    } catch (error) {
      console.warn('⚠️ Error cloning next button:', error);
    }
  }
  
  if (prevBtn && prevBtn.parentNode && typeof prevBtn.cloneNode === 'function') {
    try {
      const newPrevBtn = prevBtn.cloneNode(true);
      if (newPrevBtn && prevBtnId) {
        newPrevBtn.id = prevBtnId; // Ensure ID is preserved
      }
      if (prevBtn.parentNode) {
        prevBtn.parentNode.replaceChild(newPrevBtn, prevBtn);
      }
    } catch (error) {
      console.warn('⚠️ Error cloning prev button:', error);
    }
  }

  // Get fresh button references after cloning
  const newNextBtnRef = document.getElementById("t-nextBtn");
  const newPrevBtnRef = document.getElementById("t-prevBtn");

  if (newNextBtnRef) {
    newNextBtnRef.addEventListener("click", nextBtnHandler);
  } else {
    console.error('❌ Next button not found after cloning');
    return; // Exit if buttons are missing
  }
  
  if (newPrevBtnRef) {
    newPrevBtnRef.addEventListener("click", prevBtnHandler);
  } else {
    console.error('❌ Prev button not found after cloning');
    return; // Exit if buttons are missing
  }

  let autoplayInterval = setInterval(moveToNext, AUTOSLIDE_DURATION);
  track.autoplayInterval = autoplayInterval;

  function resetAutoplay() {
    if (track.autoplayInterval) {
      clearInterval(track.autoplayInterval);
    }
    track.autoplayInterval = setInterval(moveToNext, AUTOSLIDE_DURATION);
  }

  // Remove old mouse event listeners if they exist
  const mouseEnterHandler = () => clearInterval(track.autoplayInterval);
  const mouseLeaveHandler = () => resetAutoplay();
  
  track.addEventListener("mouseenter", mouseEnterHandler);
  track.addEventListener("mouseleave", mouseLeaveHandler);

  updateSlider(false);
  
  // Mark as initialized
  track.dataset.initialized = 'true';
}

// Initialize on DOMContentLoaded (for static content)
document.addEventListener("DOMContentLoaded", () => {
  // Don't auto-initialize - wait for API to load testimonials
  // The API loading code in index.php will call initTestimonialSlider() after loading
  try {
    const track = document.getElementById("t-sliderTrack");
    const prevBtn = document.getElementById("t-prevBtn");
    const nextBtn = document.getElementById("t-nextBtn");
    
    // Only initialize if ALL required elements exist
    if (track && prevBtn && nextBtn) {
      // Check if there are actual testimonial cards (not just loading placeholder)
      const cards = track.querySelectorAll(".t-original-card");
      const hasRealContent = cards.length > 0 && 
        !track.textContent.includes("Loading testimonials") &&
        !track.textContent.includes("No testimonials available");
      
      if (hasRealContent) {
        // Only initialize if we have real content and not from API
        // This handles cases where testimonials are statically loaded
        initTestimonialSlider();
      }
    }
  } catch (error) {
    console.warn('⚠️ Testimonial slider auto-init skipped:', error);
  }
});

// Make function globally accessible
window.initTestimonialSlider = initTestimonialSlider;
