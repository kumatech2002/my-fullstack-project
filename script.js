// Mobile menu toggle
const menuToggle = document.getElementById('mobile-menu');
const navLinks = document.querySelector('.nav-links');

menuToggle.addEventListener('click', () => {
  navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
});

document.querySelectorAll(".add-to-cart").forEach(button => {
  button.addEventListener("click", (e) => {
    const id = e.target.dataset.id;
    window.location.href = "add_to_cart.php?id=" + id;
  });
});


// Search filter
const searchInput = document.getElementById('search');
searchInput.addEventListener('input', (e) => {
  const query = e.target.value.toLowerCase();
  document.querySelectorAll('.product-card').forEach(card => {
    const title = card.querySelector('.product-title').textContent.toLowerCase();
    card.style.display = title.includes(query) ? 'block' : 'none';
  });
});

// Product details modal
const modal = document.getElementById('product-modal');
const closeModal = document.querySelector('.close-modal');

document.querySelectorAll('.view-details').forEach(button => {
  button.addEventListener('click', () => {
    document.getElementById('modal-title').textContent = button.dataset.name;
    document.getElementById('modal-price').textContent = "$" + button.dataset.price;
    document.getElementById('modal-description').textContent = button.dataset.desc;
    document.getElementById('modal-image').src = button.dataset.image;

    modal.style.display = 'flex';
  });
});

closeModal.addEventListener('click', () => {
  modal.style.display = 'none';
});

window.addEventListener('click', (e) => {
  if (e.target === modal) modal.style.display = 'none';
});

const cards = document.querySelectorAll(".product-card");

function applyFilters() {
  const cat = document.getElementById('category-filter').value;
  const price = document.getElementById('price-filter').value;
  const sort = document.getElementById('sort-filter').value;

  let min = 0, max = Infinity;
  if (price !== "all") {
    [min, max] = price.split('-').map(Number);
  }

  cards.forEach(card => {
    const category = card.dataset.category;
    const cost = parseFloat(card.dataset.price);

    const matchCategory = (cat === "all" || cat === category);
    const matchPrice = (cost >= min && cost <= max);

    card.style.display = (matchCategory && matchPrice) ? "block" : "none";
  });

  // Sorting (simple)
  const container = document.querySelector(".product-grid");
  let items = Array.from(cards);

  if (sort === "low-high") {
    items.sort((a,b) => a.dataset.price - b.dataset.price);
  } else if (sort === "high-low") {
    items.sort((a,b) => b.dataset.price - a.dataset.price);
  }

  items.forEach(i => container.appendChild(i));
}

document.getElementById('category-filter').onchange = applyFilters;
document.getElementById('price-filter').onchange = applyFilters;
document.getElementById('sort-filter').onchange = applyFilters;

