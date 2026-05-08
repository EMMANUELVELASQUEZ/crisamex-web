  </div>
</main>
<script>
document.querySelectorAll('[data-confirm]').forEach(function(b){
  b.addEventListener('click',function(e){
    if(!confirm(b.dataset.confirm)) e.preventDefault();
  });
});
</script>
</body>
</html>
