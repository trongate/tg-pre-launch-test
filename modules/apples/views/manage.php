<h1><?= $headline ?></h1>
<?php  
$num_rows = count($rows);
if ($num_rows>0) {
	?>
	<table>
		<?php  
		foreach ($rows as $row) {
			?>

			echo "<tr><td>$row['title']</td><td>";

			<?php 
		}
		?>
	</table>
	<?php 
}