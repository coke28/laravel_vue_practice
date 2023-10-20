<x-CRM-layout :pageTitle="$pageTitle" :pageDescription="$pageDescription">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body text-center">
            <h5 class="card-title text-primary">Online Users</h5>
            <p class="card-text text-secondary">Number of users currently online:</p>
            <p class="text-secondary">Online Users: <span class="card-count" id="online-user-count">Loading...</span>
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card h-100">
          <div class="card-body text-center">
            <h5 class="card-title text-primary">Number Of Active Forms</h5>
            <p class="card-text text-secondary">Number of forms currently active:</p>
            <p class="text-secondary">Active Forms: <span class="card-count" id="active-form-count">Loading...</span>
            </p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card h-100">
            <div class="card-body text-center">
              <!-- Add content for the third card -->

              <form-table-vue></form-table-vue>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 mb-4">
          <div class="card h-100">
            <div class="card-body text-center">
     
              <form-add-vue></form-add-vue>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--start::Include your modals here-->

  <!--start::Include your scripts here-->
  @section('scripts')
  <script src="{{ '/custom/broadcast/formCount.js' }}"></script>
  @endsection

  <!--start::Include your styles here-->
  @section('styles')

  @endsection
</x-CRM-layout>