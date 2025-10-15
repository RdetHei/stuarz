<h2>Edit Pengumuman</h2>
<form method="POST" action="index.php?page=update_announcement" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $announcement['id'] ?>">

  <label>Judul</label>
  <input type="text" name="title" value="<?= htmlspecialchars($announcement['title']) ?>" required class="form-control">

  <label>Isi</label>
  <textarea name="content" required class="form-control"><?= htmlspecialchars($announcement['content']) ?></textarea>

  <label>Kelas</label>
  <input type="number" name="class_id" value="<?= $announcement['class_id'] ?>" class="form-control">

  <label>Foto (opsional)</label><br>
  <?php if (!empty($announcement['photo'])): ?>
    <img src="<?= $announcement['photo'] ?>" style="max-width:150px;"><br>
  <?php endif; ?>
  <input type="file" name="photo" accept="image/*" class="form-control">

  <button type="submit" class="btn btn-primary mt-2">Update</button>
</form>
