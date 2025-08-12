function approve(btn) {
  const row = btn.parentElement.parentElement;
  row.querySelector('.status').textContent = 'Approved';
}
function reject(btn) {
  const row = btn.parentElement.parentElement;
  row.querySelector('.status').textContent = 'Rejected';
}