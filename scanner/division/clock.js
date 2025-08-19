(() => {
  // State
  let use24Hour = false;
  let showSeconds = true;

  const elDate = document.getElementById('date');
  const elTime = document.getElementById('time');
  const elAmPm = document.getElementById('ampm');
  const btn24   = document.getElementById('toggle-24h');
  const btnSec  = document.getElementById('toggle-sec');

  // Render loop
  function render() {
    const now = new Date();
    // Date e.g., "Mon, Aug 18, 2025"
    elDate.textContent = new Intl.DateTimeFormat(navigator.language, {
      weekday: 'short', month: 'short', day: '2-digit', year: 'numeric'
    }).format(now);

    // Time (locale-aware)
    const opts = {
      hour: '2-digit',
      minute: '2-digit',
      second: showSeconds ? '2-digit' : undefined,
      hour12: !use24Hour
    };

    const formatted = new Intl.DateTimeFormat(navigator.language, opts).format(now);

    // If 12h, split AM/PM; some locales put it first—handle both cases.
    if (!use24Hour) {
      // Extract AM/PM by removing digits and separators from the formatted string
      const match = formatted.match(/(AM|PM|am|pm|a\.m\.|p\.m\.)/);
      if (match) {
        // remove AM/PM token from main time
        elTime.textContent = formatted.replace(match[0], '').trim();
        elAmPm.textContent = match[0].toUpperCase().replaceAll('.', '');
        elAmPm.style.display = '';
      } else {
        elTime.textContent = formatted;
        elAmPm.style.display = 'none';
      }
    } else {
      elTime.textContent = formatted;
      elAmPm.style.display = 'none';
    }
  }

  // Controls
  btn24.addEventListener('click', () => {
    use24Hour = !use24Hour;
    btn24.setAttribute('aria-pressed', String(use24Hour));
    render();
  });

  btnSec.addEventListener('click', () => {
    showSeconds = !showSeconds;
    btnSec.setAttribute('aria-pressed', String(showSeconds));
    render();
  });

  // Start
  render();
  // Align to next whole second to avoid drift at load.
  const msToNextSecond = 1000 - (Date.now() % 1000);
  setTimeout(() => {
    render();
    setInterval(render, 1000);
  }, msToNextSecond);
})();
