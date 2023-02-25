let layer1 = document.getElementById('layer1');
let layer2 = document.getElementById('layer2');
let text = document.getElementById('text');

document.addEventListener('scroll', function() {
    let scroll = window.pageYOffset;
    layer1.style.width = (100 + scroll / 5) + '%';
    layer2.style.width = (100 + scroll / 5) + '%';
    layer2.style.left = scroll / 50 + '%';
    text.style.top = - scroll / 20 + '%';
});