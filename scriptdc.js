// function approve(row) {
//   row.nextElementSibling.textContent = 'Approved';
// }
// function reject(row) {
//   row.nextElementSibling.textContent = 'Rejected';
// }
function approve(btn) {
  const row = btn.parentElement.parentElement;
  row.querySelector('.status').textContent = 'Approved';
}
function reject(btn) {
  const row = btn.parentElement.parentElement;
  row.querySelector('.status').textContent = 'Rejected';
}