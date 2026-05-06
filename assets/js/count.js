// Achievement Counter Animation - Fixed Version
(function() {
  let hasAnimated = false; // Prevent multiple animations
  
  function initCounter() {
    const counters = document.querySelectorAll(".counter-number");
    
    if (counters.length === 0) {
      console.warn('⚠️ No counter elements found');
      return;
    }
    
    console.log('✅ Found', counters.length, 'counter elements');
    const speed = 200; // lower = faster

    const animateCounters = () => {
      if (hasAnimated) {
        console.log('⚠️ Counters already animated, skipping...');
        return;
      }
      
      hasAnimated = true;
      console.log('🚀 Starting counter animation for', counters.length, 'counters');
      
      counters.forEach((counter, index) => {
        // Get target value
        const target = parseInt(counter.getAttribute("data-target")) || 0;
        
        if (target === 0) {
          console.warn('⚠️ Counter', index, 'has invalid target:', counter.getAttribute("data-target"));
          return;
        }
        
        // Reset to 0 first
        counter.textContent = '0';
        
        let current = 0;
        const increment = Math.max(1, Math.ceil(target / speed));
        const duration = 2000; // 2 seconds total
        const steps = Math.ceil(duration / 20); // 20ms per step
        const stepIncrement = target / steps;
        
        const updateCount = () => {
          current += stepIncrement;
          
          if (current < target) {
            counter.textContent = Math.floor(current).toLocaleString();
            setTimeout(updateCount, 20);
          } else {
            counter.textContent = target.toLocaleString();
            console.log('✅ Counter', index, 'completed:', target);
          }
        };
        
        // Start animation
        setTimeout(updateCount, 50 * index); // Stagger animations
      });
    };

    // Run when section is visible
    const section = document.querySelector(".achievement-section-3");
    
    if (section && section instanceof Element) {
      console.log('✅ Achievement section found, setting up IntersectionObserver');
      
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated) {
              console.log('👁️ Achievement section is visible, starting animation');
              animateCounters();
              observer.disconnect();
            }
          });
        },
        { 
          threshold: 0.1, // Trigger when 10% visible
          rootMargin: '0px 0px -50px 0px' // Trigger slightly before fully visible
        }
      );

      try {
        observer.observe(section);
      } catch (error) {
        console.error('❌ IntersectionObserver error:', error);
        // Fallback: run animation immediately
        setTimeout(animateCounters, 500);
      }
      
      // Fallback: If section is already visible on load, animate after a delay
      setTimeout(() => {
        if (!hasAnimated && section.getBoundingClientRect().top < window.innerHeight) {
          console.log('📌 Section already visible, starting animation (fallback)');
          animateCounters();
        }
      }, 1000);
    } else {
      console.warn('⚠️ Achievement section not found, running counters immediately');
      // If section doesn't exist, run counters immediately
      setTimeout(animateCounters, 500);
    }
  }
  
  // Wait for DOM to be ready
  function startInit() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initCounter, 200);
      });
    } else {
      setTimeout(initCounter, 200);
    }
  }
  
  startInit();
})();
