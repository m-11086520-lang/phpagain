<script>
function ubahsaiz(gandaan) {
	var saiz = document.getElementById("saiz");
	saiz.style.fontSize = gandaan == 2? "1em" :
	(parseFloat(saiz.style.fontSize || 1) + (gandaan * 0.2)) + "em";
}
</script>

<b>Saiz:</b>
<button onclick="ubahsaiz(2)" title="Besarkan">Reset</button>
<button onclick="ubahsaiz(1)" title="Besarkan">A+</button>
<button onclick="ubahsaiz(-1)" title="Kecilkan">a-</button>
<button onclick="window.print()">Cetak</button>
