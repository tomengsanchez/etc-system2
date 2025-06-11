<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Role Management</h1>
  <a href="<?php echo URLROOT; ?>/role/create" class="btn btn-primary">
      <i class="fa fa-pencil"></i> Add New Role
  </a>
</div>
<?php flash('success'); ?>
<?php flash('danger'); ?>
<div class="card card-body">
  <table class="table table-striped">
      <thead>
      <tr>
          <th>ID</th>
          <th>Name</th>
          <th class="text-right">Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($data['roles'] as $role) : ?>
          <tr>
          <td><?php echo $role->id; ?></td>
          <td><?php echo $role->name; ?></td>
          <td class="text-right">
              <a href="<?php echo URLROOT; ?>/role/edit/<?php echo $role->id; ?>" class="btn btn-dark">Edit</a>
              <form class="d-inline" action="<?php echo URLROOT; ?>/role/delete/<?php echo $role->id; ?>" method="post">
              <input type="submit" value="Delete" class="btn btn-danger">
              </form>
          </td>
          </tr>
      <?php endforeach; ?>
      </tbody>
  </table>
</div>
