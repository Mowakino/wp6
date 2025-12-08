/* Page loading skeleton */
setTimeout(() => {
    document.getElementById("skeleton").style.display = "none";
    document.getElementById("content").style.display = "block";
}, 500);

/* STAR RATING */
const stars = document.querySelectorAll("#star-rating span");
const ratingInput = document.getElementById("rating-value");

if (stars.length) {
    stars.forEach(star => {
        star.addEventListener("mouseover", () => highlight(star.dataset.value));
        star.addEventListener("mouseout", () => highlight(ratingInput.value));
        star.addEventListener("click", () => {
            ratingInput.value = star.dataset.value;
            highlight(ratingInput.value);
        });
    });
}

function highlight(v) {
    stars.forEach(s => {
        s.classList.toggle("selected", s.dataset.value <= v);
    });
}

/* Reply toggle */
function toggleReply(id){
    let box = document.getElementById("reply-"+id);
    box.style.maxHeight = box.style.maxHeight ? null : box.scrollHeight+"px";
}

/* Edit toggle */
function toggleEdit(id){
    let box = document.getElementById("edit-"+id);
    box.style.maxHeight = box.style.maxHeight ? null : box.scrollHeight+"px";
}

/* Toast */
function toast(msg,type="success"){
    const t=document.getElementById("toast");
    t.innerText=msg;
    t.style.background = type==="error" ? "#e74c3c" : "#4CAF50";
    t.classList.add("show");
    setTimeout(()=> t.classList.remove("show"),2500);
}

/* SORT + RATING FILTER */
function applyFilters() {
    const url = new URL(window.location.href);

    let sort = document.getElementById("sortSelect").value;
    let rating = document.getElementById("ratingSelect").value;

    url.searchParams.set("sort", sort);

    if (rating) url.searchParams.set("rating", rating);
    else url.searchParams.delete("rating");

    window.location = url.toString();
}
