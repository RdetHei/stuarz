// ...new file...
document.addEventListener('DOMContentLoaded', () => {
  // add sort buttons for "Level" header and implement sort
  const ths = Array.from(document.querySelectorAll('table thead th'));
  let levelIndex = -1;
  ths.forEach((th, idx) => {
    if (th.textContent.trim().toLowerCase() === 'level') levelIndex = idx;
  });
  if (levelIndex === -1) return;

  const levelHeader = ths[levelIndex];
  const btnAsc = document.createElement('button');
  const btnDesc = document.createElement('button');
  btnAsc.type = 'button'; btnDesc.type = 'button';
  btnAsc.textContent = '↑'; btnDesc.textContent = '↓';
  btnAsc.className = 'ml-2 text-xs px-2'; btnDesc.className = 'ml-1 text-xs px-2';

  const sortByLevel = (asc = true) => {
    const table = document.querySelector('table');
    if (!table) return;
    const tbody = table.tBodies[0];
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.sort((a,b) => {
      const aText = (a.children[levelIndex]?.textContent || '').trim().toLowerCase();
      const bText = (b.children[levelIndex]?.textContent || '').trim().toLowerCase();
      if (aText === bText) return 0;
      return (aText > bText ? 1 : -1) * (asc ? 1 : -1);
    });
    rows.forEach(r => tbody.appendChild(r));
  };

  btnAsc.addEventListener('click', () => sortByLevel(true));
  btnDesc.addEventListener('click', () => sortByLevel(false));

  levelHeader.appendChild(btnAsc);
  levelHeader.appendChild(btnDesc);
});