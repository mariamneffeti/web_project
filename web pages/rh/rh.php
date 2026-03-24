<?php 
    $pageTitle = "Employees"; 
    include('../squelettes entreprise/header.php'); 
 ?>
<main class="container page-content">
  <section class="card shadow-sm mb-5">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3 class="mb-4">Add New Employee</h3>

      <form class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input type="text" class="form-control" placeholder="Employee name">
        </div>

        <div class="col-md-6">
          <label class="form-label">Department</label>
          <select class="form-select">
            <option selected>Choose...</option>
            <option>IT</option>
            <option>Finance</option>
            <option>Marketing</option>
            <option>Human Resources</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input type="text" class="form-control" placeholder="Position">
        </div>

        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" placeholder="email@company.com">
        </div>

        <div class="col-md-12">
          <label class="form-label">CV Link</label>
          <input type="url" class="form-control" placeholder="https://example.com/cv.pdf">
        </div>

        <div class="col-12 text-end">
          <button class="btn btn-primary">Add Employee</button>
        </div>
      </form>
    </div>
  </section>

  <section class="row mb-4">
    <div class="col-md-6 mb-2">
      <input type="text" class="form-control" placeholder="Search by name or ID">
    </div>
    <div class="col-md-4 mb-2">
      <select class="form-select">
        <option selected>Filter by...</option>
        <option>Department</option>
        <option>Position</option>
        <option>Status</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-secondary w-100">Apply</button>
    </div>
  </section>

  <section class="tab">
    <div class="card p-4 stats-card h-100" style="border-left: 4px solid #388087;">
      <h3>Employee List</h3>
      <div >
        <table>
          <thead >
            <tr>
              <th>Name</th>
              <th>ID</th>
              <th>Position</th>
              <th>Department</th>
              <th>Email</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Sara Dh</td>
              <td>05A</td>
              <td>Web Developer</td>
              <td>IT</td>
              <td>sara@company.com</td>
              <td><span class="badge bg-success">Active</span></td>
              <td>
                <button class="btn btn-sm btn-outline-primary">View</button>
                <button class="btn btn-sm btn-outline-warning">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </td>
            </tr>

            <tr>
              <td>Alex Rv</td>
              <td>05B</td>
              <td>HR Manager</td>
              <td>HR</td>
              <td>alex@company.com</td>
              <td><span class="badge bg-secondary">Inactive</span></td>
              <td>
                <button class="btn btn-sm btn-outline-primary">View</button>
                <button class="btn btn-sm btn-outline-warning">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

</main>
<?php include('../squelettes entreprise/footer.php'); ?>
