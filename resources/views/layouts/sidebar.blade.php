<aside id="sidebar" class="sidebar">
  <!-- Logo Section -->
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('home') }}" class="logo d-flex align-items-center text-center">
      <span class="d-none d-lg-block text-white">Atreya Webs</span>
    </a>
  </div>


  <!-- Sidebar Navigation -->
  <nav>
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a href="{{ route('home') }}"
          class="nav-link {{ Route::currentRouteName() === 'StartPage' ? 'active' : 'collapsed' }}">
          <i class="bi bi-house-door"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <!-- Album Section -->
      <!-- <li class="nav-item">
        <a href="#organisation-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['image-groups.create', 'showUploadPage' ]) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#organisation-nav">
          <i class="bi bi-buildings"></i>
          <span>Image Group</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="organisation-nav"
          class="nav-content collapse ms-2 {{ in_array(Route::currentRouteName(), ['image-groups.create', 'showUploadPage' ]) ? 'show' : '' }}"
          data-bs-parent="#sidebar-nav">

          <li class="mb-1">
            <a href="{{ route('image-groups.create') }}"
              class="{{ Route::currentRouteName() === 'image-groups.create' }}">
              <i class="bi bi-circle"></i>
              <span>Create Image Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('showUploadPage') }}"
              class="{{ Route::currentRouteName() === 'showUploadPage' }}">
              <i class="bi bi-circle"></i>
              <span>Upload Image</span>
            </a>
          </li>
        </ul>
      </li> -->
      <!-- Update Section -->
      <li class="mb-1">
        <a href="#updates-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CreateUpdatesPage', 'UpdateListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#updates-nav">
          <i class="bi bi-newspaper"></i>
          <span>Updates</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="updates-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CreateUpdatesPage', 'UpdateListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CreateUpdatesPage') }}"
              class="{{ Route::currentRouteName() === 'CreateUpdatesPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add updates </span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('UpdateListPage') }}"
              class="{{ Route::currentRouteName() === 'UpdateListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Updates List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Content Section -->
      <li class="mb-1">
        <a href="#content-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['ContentListPage', 'createContentPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#content-nav">
          <i class="bi bi-box-seam-fill"></i>
          <span>Content</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="content-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['ContentListPage', 'createContentPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('createContentPage') }}"
              class="{{ Route::currentRouteName() === 'createContentPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Content</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('ContentListPage') }}"
              class="{{ Route::currentRouteName() === 'ContentListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Content List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Activity Dropdown -->
      <li class="mb-1">
        <a href="#activity-master-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['ActivityGroupPage', 'CreateActivityPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#activity-master-nav">
          <i class="bi bi-newspaper"></i>
          <span>Activity</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="activity-master-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['ActivityGroupPage', 'CreateActivityPage', 'ActivityListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('ActivityGroupPage') }}"
              class="{{ Route::currentRouteName() === 'ActivityGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add Activity Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('CreateActivityPage') }}"
              class="{{ Route::currentRouteName() === 'CreateActivityPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add Activity</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('ActivityListPage') }}"
              class="{{ Route::currentRouteName() === 'ActivityListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Activity List </span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Facility Dropdown -->
      <li class="mb-1">
        <a href="#facility-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['createFacilityPage', 'FacilityListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#facility-nav">
          <i class="bi bi-boxes"></i>
          <span>Facility</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="facility-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['createFacilityPage', 'FacilityListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('createFacilityPage') }}"
              class="{{ Route::currentRouteName() === 'createFacilityPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Facility</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('FacilityListPage') }}"
              class="{{ Route::currentRouteName() === 'FacilityListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Facility List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Department Section -->
      <li class="mb-1">
        <a href="#department-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['createDepartmentPage', 'DepartmentListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#department-nav">
          <i class="bi bi-houses"></i>
          <span>Department</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="department-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['createDepartmentPage', 'DepartmentListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('createDepartmentPage') }}"
              class="{{ Route::currentRouteName() === 'createDepartmentPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Department</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('DepartmentListPage') }}"
              class="{{ Route::currentRouteName() === 'DepartmentListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Department List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Course Dropdown -->
      <li class="mb-1">
        <a href="#course-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['createCoursePage', 'CourseListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#course-nav">
          <i class="bi bi-box-seam-fill"></i>
          <span>Course</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="course-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['createCoursePage', 'CourseListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('createCoursePage') }}"
              class="{{ Route::currentRouteName() === 'createCoursePage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Course</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('CourseListPage') }}"
              class="{{ Route::currentRouteName() === 'CourseListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Course List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Committee Section -->
      <li class="mb-1">
        <a href="#committee-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CreateCommitteePage', 'CommitteeListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#committee-nav">
          <i class="bi bi-houses"></i>
          <span>Committee</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="committee-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CreateCommitteePage', 'CommitteeListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CreateCommitteePage') }}"
              class="{{ Route::currentRouteName() === 'CreateCommitteePage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Comittee</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('CommitteeListPage') }}"
              class="{{ Route::currentRouteName() === 'CommitteeListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Committee List</span>
            </a>
          </li>
        </ul>
      </li>

      <p>Master</p>
      <!-- Message Section -->
      <li class="mb-1">
        <a href="#message-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CreateMassege', 'MassageList']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#message-nav">
          <i class="fas fa-envelope-open-text mr-2"></i>
          <span>Messages</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="message-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CreateMassege', 'MassageList']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CreateMassege') }}"
              class="{{ Route::currentRouteName() === 'CreateMassege' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add Message</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('MassageList') }}"
              class="{{ Route::currentRouteName() === 'MassageList' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Message List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Manage Album -->
      <li class="mb-1">
        <a href="#album-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['ContentListPage', 'createContentPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#album-nav">
          <i class="bi bi-box-seam-fill"></i>
          <span>Manage Album</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="album-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CreateGallery', 'GalleryListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CreateGallery') }}"
              class="{{ Route::currentRouteName() === 'CreateGallery' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Album</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('GalleryListPage') }}"
              class="{{ Route::currentRouteName() === 'GalleryListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Album List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Manage Video -->
      <li class="mb-1">
        <a href="#video-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CreateVideoPage', 'VideoListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#video-nav">
          <i class="bi bi-box-seam-fill"></i>
          <span>Manage Video</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="video-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CreateVideoPage', 'VideoListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CreateVideoPage') }}"
              class="{{ Route::currentRouteName() === 'CreateVideoPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Video</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('VideoListPage') }}"
              class="{{ Route::currentRouteName() === 'VideoListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Video List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- News Dropdown -->
      <li class="mb-1">
        <a href="#news-category-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['ScrollingNewsCreatePage', 'NewsListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#news-category-nav">
          <i class="bi bi-newspaper"></i>
          <span>Scrolling News</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="news-category-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['ScrollingNewsCreatePage', 'NewsListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('ScrollingNewsCreatePage') }}"
              class="{{ Route::currentRouteName() === 'ScrollingNewsCreatePage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create News</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('NewsListPage') }}"
              class="{{ Route::currentRouteName() === 'NewsListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>News List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Testimonial Dropdown -->
      <li class="mb-1">
        <a href="#testimonial-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['getTestimonialsPage', 'TestimonialListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#testimonial-nav">
          <i class="bi bi-chat-left-text-fill"></i>
          <span>Testimonial</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="testimonial-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['getTestimonialsPage', 'TestimonialListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('getTestimonialsPage') }}"
              class="{{ Route::currentRouteName() === 'getTestimonialsPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Testimonial's</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('TestimonialListPage') }}"
              class="{{ Route::currentRouteName() === 'TestimonialListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Testimonial List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Achiever Dropdowns -->
      <li class="mb-1">
        <a href="#achivers-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['getAchiversPage', 'AchiversListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#achivers-nav">
          <i class="bi bi-telephone"></i>
          <span>Achivers</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="achivers-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['getAchiversPage', 'AchiversListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('getAchiversPage') }}"
              class="{{ Route::currentRouteName() === 'getAchiversPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Achiver's</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('AchiversListPage') }}"
              class="{{ Route::currentRouteName() === 'AchiversListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Achiver List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- FAQ Dropdowns -->
      <li class="mb-1">
        <a href="#faq-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['FAQmasterPage', 'FAQmasterListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#faq-nav">
          <i class="bi bi-question-octagon"></i>
          <span>Frequently Asked Question</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="faq-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['FAQmasterPage', 'FAQmasterListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('FAQmasterPage') }}"
              class="{{ Route::currentRouteName() === 'FAQmasterPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create FAQ </span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('FAQmasterListPage') }}"
              class="{{ Route::currentRouteName() === 'FAQmasterListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>FAQ List</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="{{ route('DesignationMaster') }}"
          class="nav-link {{ Route::currentRouteName() === 'DesignationMaster' ? 'active' : '' }}">
          <i class="bi bi-circle"></i>
          <span>Designation Master</span>
        </a>
      </li>
      <!-- Inquiry Dropdown -->
      <li class="mb-1">
        <a href="#inquiry-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CreateContactPage', 'ContactListpage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#inquiry-nav">
          <i class="bi bi-telephone"></i>
          <span>Inquiry</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="inquiry-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CreateContactPage', 'ContactListpage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CreateContactPage') }}"
              class="{{ Route::currentRouteName() === 'CreateContactPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Contact</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('ContactListpage') }}"
              class="{{ Route::currentRouteName() === 'ContactListpage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Contact List</span>
            </a>
          </li>
        </ul>
      </li>
      <p>Group master</p>
      <!-- Facility Group -->
      <li class="mb-1">
        <a href="#facility-master-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['FacilityGroupPage', 'FacilityGroupPageListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#facility-master-nav">
          <i class="bi bi-gear-wide-connected"></i>
          <span>Facility</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="facility-master-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['FacilityGroupPage', 'FacilityGroupPageListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('FacilityGroupPage') }}"
              class="{{ Route::currentRouteName() === 'FacilityGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('FacilityGroupPageListPage') }}"
              class="{{ Route::currentRouteName() === 'FacilityGroupPageListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Group List</span>
            </a>
          </li>
        </ul>
      </li>
      <!-- Course Group -->
      <li class="mb-1">
        <a href="#Course-master-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CourseGroupPage', 'CourseGroupPageListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#Course-master-nav">
          <i class="bi bi-clipboard-data-fill"></i>
          <span>Course Group</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="Course-master-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CourseGroupPage', 'CourseGroupPageListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CourseGroupPage') }}"
              class="{{ Route::currentRouteName() === 'CourseGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Course Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('CourseGroupPageListPage') }}"
              class="{{ Route::currentRouteName() === 'CourseGroupPageListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Course Group List</span>
            </a>
          </li>
        </ul>
      </li>
      <!---- Department Group ------>
      <li class="mb-1">
        <a href="#department-master-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['DepartmentGroupPage', 'DepartmentGroupPageListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#department-master-nav">
          <i class="bi bi-gear-wide-connected"></i>
          <span>Department Group</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="department-master-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['DepartmentGroupPage', 'DepartmentGroupPageListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('DepartmentGroupPage') }}"
              class="{{ Route::currentRouteName() === 'DepartmentGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Department Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('DepartmentGroupPageListPage') }}"
              class="{{ Route::currentRouteName() === 'DepartmentGroupPageListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Department Group List</span>
            </a>
          </li>
        </ul>
      </li>
      <!---- Committee Group ------>
      <li class="mb-1">
        <a href="#committee-master-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CommitteeGroupPage', 'CommitteeGroupPageListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#committee-master-nav">
          <i class="bi bi-gear-wide-connected"></i>
          <span>Committee Group</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="committee-master-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CommitteeGroupPage', 'CommitteeGroupPageListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CommitteeGroupPage') }}"
              class="{{ Route::currentRouteName() === 'CommitteeGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Committee Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('CommitteeGroupPageListPage') }}"
              class="{{ Route::currentRouteName() === 'CommitteeGroupPageListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Committee Group List</span>
            </a>
          </li>
        </ul>
      </li>
      <!---- Content Group ------>
      <li class="mb-1">
        <a href="#content-mastre-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['ContentGroupPage', 'ContentGroupPageListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#content-mastre-nav">
          <i class="bi bi-houses"></i>
          <span>Content Group</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="content-mastre-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['ContentGroupPage', 'ContentGroupPageListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('ContentGroupPage') }}"
              class="{{ Route::currentRouteName() === 'ContentGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Create Content Group</span>
            </a>
          </li>
          <li class="mb-1">
            <a href="{{ route('ContentGroupPageListPage') }}"
              class="{{ Route::currentRouteName() === 'ContentGroupPageListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Content Group List</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>


  </nav>
</aside>