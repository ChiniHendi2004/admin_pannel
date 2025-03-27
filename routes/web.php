<?php

use Illuminate\Http\Request;
use App\Http\Controllers\WEB\Video;
use App\Http\Controllers\WEB\Course;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WEB\Content;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WEB\Activity;
use App\Http\Controllers\WEB\Facility;
use App\Http\Controllers\WEB\Committee;
use App\Http\Controllers\WEB\Department;
use App\Http\Controllers\WEB\ManageNews;
use App\Http\Controllers\WEB\AlbumMaster;
use App\Http\Controllers\WEB\ManageAlbum;
use App\Http\Controllers\WEB\ContentGroup;
use App\Http\Controllers\WEB\CourseGroups;
use App\Http\Controllers\WEB\ActivityGroup;
use App\Http\Controllers\WEB\FacilityGroup;
use App\Http\Controllers\WEB\FAQController;
use App\Http\Controllers\WEB\ManageInquiry;
use App\Http\Controllers\WEB\ManageMassage;
use App\Http\Controllers\WEB\CommitteeGroup;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\WEB\DepartmentGroup;
use App\Http\Controllers\WEB\ImageController;
use App\Http\Controllers\WEB\ManageAchievers;
use App\Http\Controllers\WEB\ManageTestimonial;
use App\Http\Controllers\WEB\UpdatesController;
use App\Http\Controllers\WEB\ManageAlbumContent;
use App\Http\Controllers\WEB\ManageVideoContent;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\WEB\OrganisationController;
use App\Http\Controllers\WEB\ClientImageGroupController;
use App\Http\Controllers\WEB\DesignationMasterController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\TenantAuthController;


Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('BackendPages.Dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('homes');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Image Group Management
    Route::prefix('image-groups')->group(function () {
        Route::get('/create', [ClientImageGroupController::class, 'create'])->name('image-groups.create');
        Route::get('/', [ClientImageGroupController::class, 'index']);
        Route::post('/', [ClientImageGroupController::class, 'store']);
        Route::put('/{id}', [ClientImageGroupController::class, 'update']);
        Route::delete('/{id}', [ClientImageGroupController::class, 'destroy']);
        Route::put('/update-status/{id}', [ClientImageGroupController::class, 'updateStatus']);
    });

    // Upload Image to Group Item
    Route::prefix('upload-image')->group(function () {
        Route::get('/page', [ImageController::class, 'showUploadPage'])->name('showUploadPage');
        Route::get('/get-items/{groupType}', [ImageController::class, 'getItems']);
        Route::post('/', [ImageController::class, 'uploadImage']);
        Route::get('/get-images', [ImageController::class, 'getImages']);
        Route::delete('/{id}', [ImageController::class, 'deleteImage']);
        Route::put('/update-status/{id}', [ImageController::class, 'updateImageStatus']);
    });

    // Organisation Routes (keep public if intended)
    Route::get('/organization/create', [OrganisationController::class, 'create'])->name('organization.create');
    Route::post('/organization/store', [OrganisationController::class, 'store'])->name('organization.store');


    // ------------------------------------Updates
    Route::get('/Create/Updates/Page', [UpdatesController::class, 'getUpdatesPage'])->name('CreateUpdatesPage'); // Fetch all updates
    Route::get('/Edit/Updates/Page/{id}', [UpdatesController::class, 'editUpdatesPage'])->name('EditUpdatesPage'); // Fetch all updates
    Route::get('/Updates/List/Page', [UpdatesController::class, 'getUpdatesListPage'])->name('UpdateListPage'); // Fetch all updates
    Route::get('/Updates/Content/Page/{id}', [UpdatesController::class, 'addContentPage']); // Fetch all updates
    Route::get('/View/Updates/Single/Content/Page/{update_id}/{paragraph_no}', [UpdatesController::class, 'getSingleContent']); // Fetch all updates
    Route::get('/View/Updates/Content/Page/{id}', [UpdatesController::class, 'ViewUpdatespage']); // Fetch all updates
    Route::get('/Updates/Index', [UpdatesController::class, 'index']); // Fetch all updates
    Route::post('/Create/Updates', [UpdatesController::class, 'create']); // Create a new update

    Route::get('/Updates/List', [UpdatesController::class, 'updatesList'])->name('UpdateList'); // Fetch a specific update by ID
    Route::get('/Updates/Select/{id}', [UpdatesController::class, 'selectIdWise']); // Fetch a specific update by ID
    Route::get('/Select/Updates/Details/{id}/{no}', [UpdatesController::class, 'selectUpdatesDetails'])->name('selectUpdatesDetails');
    Route::post('/Edit/Updates/{id}', [UpdatesController::class, 'editUpdates']); // Update a specific update
    Route::delete('/Delete/Updates/{id}', [UpdatesController::class, 'destroyUpdates']); // Delete a specific update
    Route::get('/Edit-Updates/Get/Content/{id}', [UpdatesController::class, 'getUpdatesDetails'])->name('GetUpdatesDetails');
    Route::post('/Updates/Add/Details', [UpdatesController::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::get('/Updates-Details/List/{id}', [UpdatesController::class, 'getAllDetails'])->name('UpdatesDetailsList');
    Route::delete('/Updates-Details/Remove/{id}', [UpdatesController::class, 'deleteDetails'])->name('DeleteDetails');


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //---------------------------------------------------------------Content Group pages-------------------------------------------//
    Route::get('/Content-Group/Create', [ContentGroup::class, 'CreateGroupPage'])->name('ContentGroupPage');
    Route::get('/Content-Group/List/Page', [ContentGroup::class, 'ContentgroupList'])->name('ContentGroupPageListPage');
    //-------------------------------------api codes-----------------------------------------------
    Route::post('/Content-Group/Add', [ContentGroup::class, 'addContentGroups'])->name('ContentGroupAdd');
    Route::get('/Content-Group/List', [ContentGroup::class, 'contentList'])->name('ContentGroupList');
    Route::get('/Content-Group/List/Latest', [ContentGroup::class, 'contentListLatest'])->name('ContentGroupListLatest');
    Route::put('/Content-Group/Edit', [ContentGroup::class, 'editContentGroup'])->name('ContentGroupEdit');
    Route::get('/Content-Group/Select/{id}', [ContentGroup::class, 'groupDetail'])->name('ContentGroupDetail');
    Route::patch('/Content-Group/EditStatus/{id}', [ContentGroup::class, 'statusEdit'])->name('ContentGroupStsEdit');
    Route::delete('/Content-Group/Remove/{id}', [ContentGroup::class, 'removeUpdates'])->name('ContentGroupRemove');
    Route::get('/Content-Group/StatuswiseList', [ContentGroup::class, 'activeGroupList'])->name('ContentGroupStatusList');
    //----------------------------------------------------Content pages------------------------------------------------------------------//
    Route::get('/Create-Content', [Content::class, 'CreatePage'])->name('createContentPage');
    Route::get('/Content-list/Page', [Content::class, 'contentListPage'])->name('ContentListPage');
    Route::get('/Content-Details/Page/{id}', [Content::class, 'addMoreDetails'])->name('ContentDetailsPage');
    Route::get('/Edit-Content/{id}', [Content::class, 'contentUpdatePage'])->name('EditContentPage');
    Route::get('/View-Content/Page/{id}', [Content::class, 'ViewContentPage'])->name('ViewContentPage');
    Route::get('/View-Content/single/Details/Page/{id}/{paragraphid}', [Content::class, 'getViewSingleDetails'])->name('GetSingleContentDetailsPage');
    //--------------------codes--------------------------------------------
    Route::post('/Create-Content/Add', [Content::class, 'createContent'])->name('CreateContent');
    Route::get('/Content/List', [Content::class, 'contentList'])->name('ContentList');
    Route::get('/Content/Get/Title/{id}', [Content::class, 'getTitleDetail'])->name('ContentTitle');
    Route::post('/Edit-Content/{id}', [Content::class, 'updateContent'])->name('EditContent');
    Route::get('/Edit-Content/Get/Details/List/{id}', [Content::class, 'getContentDetails'])->name('GetContentDetails');
    Route::post('/Edit-Content/Add/Details', [Content::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::post('/Content/Paragraph/Remove', [Content::class, 'removeContent'])->name('content.remove');
    Route::get('/Content/detail/{id}', [Content::class, 'getAllDetails'])->name('ContentDetail');
    Route::delete('/Content/remove/{id}', [Content::class, 'deleteContent'])->name('ContentDelete');

    // ---------------------------------Activity
    // ----------------------------------Group
    Route::get('/Create/ActivityGroup/Page', [ActivityGroup::class, 'createGroupPage'])->name('ActivityGroupPage');
    Route::post('/Create/Activity/Group', [ActivityGroup::class, 'createActivityGroups']); // Create a new update
    Route::get('/Activity/Group/List', [ActivityGroup::class, 'activityGroupList']); // Fetch a specific update by ID
    Route::get('/Activity/Group/Select/{id}', [ActivityGroup::class, 'selectIdWise']); // Fetch a specific update by ID
    Route::put('/Edit/Activity/Group/{id}', [ActivityGroup::class, 'editActivityGroup']); // Update a specific update
    Route::patch('/Activity/Group/EditStatus/{id}', [ActivityGroup::class, 'statusEdit'])->name('ActivityGroupStsEdit');
    Route::get('/Activity/Group/Active/Status/List', [ActivityGroup::class, 'activeGroupList'])->name('FacilityGroupStatusList');
    Route::delete('/Delete/Activity/Group/{id}', [ActivityGroup::class, 'removeUpdates']); // Delete a specific update
    // --------------------------------Activity-------------------------------------------------------------------------
    Route::get('/Create/Activity/Page', [Activity::class, 'activityPage'])->name('CreateActivityPage');
    Route::get('/Activity/List/Page', [Activity::class, 'activityListPage'])->name('ActivityListPage');
    Route::get('/Edit/Activity/Page/{id}', [Activity::class, 'editActivityPage']);
    Route::get('/Activity/Add/Details/Page/{id}', [Activity::class, 'addMoreDetailsPage']);
    Route::get('/View/Activity/Page/{id}', [Activity::class, 'ViewActivitypage']);
    Route::get('/View/Activity/single/Details/Page/{id}/{paragraph_no}', [Activity::class, 'ViewSingleDetails']);
    Route::post('/Create/Activity', [Activity::class, 'createActivity']); // Create a new update
    Route::get('/Activity/List', [Activity::class, 'activityList']); // Fetch a specific update by ID
    Route::get('/Activity/Select/{id}', [Activity::class, 'selectIdWise']); // Fetch a specific update by ID
    Route::post('/Edit/Activity/{id}', [Activity::class, 'editActivity']); // Update a specific update
    Route::delete('/Delete/Activity/{id}', [Activity::class, 'destroy']); // Delete a specific update
    Route::delete('/Delete/Activity/Details/{id}', [Activity::class, 'deleteContent']); // Delete a specific update
    Route::post('/Activity/Add/Details', [Activity::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::get('/Activity/Get/Details/{id}', [Activity::class, 'getActivityDetails'])->name('GetDetails');
    Route::get('/Activity/Details/List/{id}', [Activity::class, 'getAllDetails'])->name('ActivityDetailsList'); // ---------------------------------------------------------------------------------------------

    //---------------------------------------------------------------Facility Group pages-------------------------------------------//
    Route::get('/Facility-Group/Create', [FacilityGroup::class, 'CreateGroupPage'])->name('FacilityGroupPage');
    Route::get('/Facility-Group/List/Page', [FacilityGroup::class, 'FacilitygroupList'])->name('FacilityGroupPageListPage');
    //-------------------------------------api codes-----------------------------------------------
    Route::post('/Facility-Group/Add', [FacilityGroup::class, 'addFacilityGroups'])->name('FacilityGroupAdd');
    Route::get('/Facility-Group/List', [FacilityGroup::class, 'facilityList'])->name('FacilityGroupList');
    Route::get('/Facility-Group/List/Latest', [FacilityGroup::class, 'facilityListLatest'])->name('FacilityGroupListLatest');
    Route::put('/Facility-Group/Edit', [FacilityGroup::class, 'editFacilityGroup'])->name('FacilityGroupEdit');
    Route::get('/Facility-Group/Select/{id}', [FacilityGroup::class, 'groupDetail'])->name('FacilityGroupDetail');
    Route::patch('/Facility-Group/EditStatus/{id}', [FacilityGroup::class, 'statusEdit'])->name('FacilityGroupStsEdit');
    Route::delete('/Facility-Group/Remove/{id}', [FacilityGroup::class, 'removeFacilityGroups'])->name('FacilityGroupRemove');
    Route::get('/Facility-Group/StatuswiseList', [FacilityGroup::class, 'activeGroupList'])->name('FacilityGroupStatusList');
    //----------------------------------------------------pages------------------------------------------------------------------//
    Route::get('/Create-Facility', [Facility::class, 'CreatePage'])->name('createFacilityPage');
    Route::get('/Facility-list/Page', [Facility::class, 'facilityListPage'])->name('FacilityListPage');
    Route::get('/Facility-Details/Page/{id}', [Facility::class, 'addMoreDetails'])->name('FacityDetails');
    Route::get('/Edit-Facility/{id}', [Facility::class, 'facilityUpdatePage'])->name('EditFacility');
    Route::get('/View-Facility/Page/{id}', [Facility::class, 'ViewfacilityPage'])->name('ViewFacility');
    Route::get('/View-Facility/single/Details/Page/{id}/{paragraphid}', [Facility::class, 'getViewSingleDetails'])->name('GetSingleFacilityDetails');
    //--------------------codes--------------------------------------------
    Route::post('/Create-Facility/Add', [Facility::class, 'createFacility'])->name('CreateFacility');
    Route::get('/Facility/List', [Facility::class, 'FacilityList'])->name('FacilityList');
    Route::get('/Facility/Get/title/{id}', [Facility::class, 'getTitleDetail'])->name('FacilitySelect');
    Route::post('/Edit-Facility/Class/{id}', [Facility::class, 'editFacilityTitle'])->name('EditFacilityPost');
    Route::get('/Edit-Facility/Class/{id}', [Facility::class, 'editFacilityTitle'])->name('EditFacilityGet');
    Route::get('/Edit-Facility/Get/Details/{id}', [Facility::class, 'getFacilityDetails'])->name('GetFacilityDetailsParagraphOrder');
    Route::post('/Edit-Facility/Add/Details', [Facility::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::get('/Edit-Facility/Details/List/{id}', [Facility::class, 'getAllDetails'])->name('DepartmentDetailsList');
    Route::delete('Facilities/Remove/List/{id}', [Facility::class, 'removeFacility'])->name('RemoveFacility');
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //------------------------------------------------------------Manage Testimonial------------------------------------------//
    Route::get('/Manage-Testimonial/Page', [ManageTestimonial::class, 'getTestimonialsPage'])->name('getTestimonialsPage');
    Route::get('/Manage-Testimonial/Update/Page/{id}', [ManageTestimonial::class, 'TestimonialUpdatePage'])->name('TestimonialUpdatePage');
    Route::get('/Manage-Testimonial/List/Page', [ManageTestimonial::class, 'TestimonialListPage'])->name('TestimonialListPage');
    Route::post('/Manage-Testimonial/Add', [ManageTestimonial::class, 'addTestimonials'])->name('AddTestimonials');
    Route::get('/Manage-Testimonial/List/{tenant_id}', [ManageTestimonial::class, 'TestimonialList'])->name('TestimonialList');
    Route::get('/Manage-Testimonial/List/latest', [ManageTestimonial::class, 'TestimonialListlatest'])->name('TestimonialListlatest');
    Route::get('/Manage-Testimonial/Select/{id}', [ManageTestimonial::class, 'TestimonialDetails'])->name('TestimonialSelect');
    Route::put('/Manage-Testimonial/Update/{id}', [ManageTestimonial::class, 'updateTestimonial'])->name('updateTestimonial');
    Route::delete('/Manage-Testimonial/Remove/{id}', [ManageTestimonial::class, 'deleteTestimonial'])->name('deleteTestimonial');


    //-------------------------------------------------------------------Department Group----------------------------------------//
    //--------------------------pages--------------------------------------//
    Route::get('/Department-Group', [DepartmentGroup::class, 'getPage'])->name('DepartmentGroupPage');
    Route::get('/Department-Group/List/Page', [DepartmentGroup::class, 'groupList'])->name('DepartmentGroupPageListPage');
    //------------------------------------Codes------------------------------------//
    Route::post('/Department-Group/Add', [DepartmentGroup::class, 'addDepartmentGroups'])->name('DepartmentGroupAdd');
    Route::get('/Department-Group/List', [DepartmentGroup::class, 'deparmentList'])->name('DepartmentGroupList');
    Route::get('/Department-Group/List/Latest', [DepartmentGroup::class, 'deparmentListLatest'])->name('DepartmentGroupListLatest');
    Route::put('/Department-Group/Edit', [DepartmentGroup::class, 'editDepartmentGroup'])->name('DepartmentGroupEdit');
    Route::get('/Department-Group/Select/{id}', [DepartmentGroup::class, 'groupDetail'])->name('DepartmentGroupDetail');
    Route::patch('/Department-Group/EditStatus/{id}', [DepartmentGroup::class, 'statusEdit'])->name('DepartmentGroupStsEdit');
    Route::delete('/Department-Group/Remove/{id}', [DepartmentGroup::class, 'removeUpdates'])->name('DepartmentGroupRemove');
    Route::get('/Department-Group/StatuswiseList', [DepartmentGroup::class, 'activeGroupList'])->name('DepartmentGroupStatusList');
    //----------------------------------------------------------------------Department and Details----------------------------//
    //--------------------pages--------------------------------------------//
    Route::get('/Create-Department', [Department::class, 'getPage'])->name('createDepartmentPage');
    Route::get('/Department-list/Page', [Department::class, 'departmentListPage'])->name('DepartmentListPage');
    Route::get('/Department-Details/Page/{id}', [Department::class, 'addMoreDetails'])->name('DepartmentDetails');
    Route::get('/Edit-Department/{id}', [Department::class, 'departmentUpdatePage'])->name('EditDepartment');
    Route::get('/View-Departments/Page/{id}', [Department::class, 'ViewDepartmentPage'])->name('ViewDepartments');
    Route::get('/View-Department/single/Details/Page/{id}/{paragraphid}', [Department::class, 'getViewSingleDetails'])->name('GetSingleDepartmentDetails');
    Route::delete('/Department/Remove/{id}', [Department::class, 'RemoveDepartment'])->name('RemoveDepartment');
    //------------------------------------Codes------------------------------------//
    Route::post('/Create-Department/Add', [Department::class, 'createDepartment'])->name('CreateDepartment');
    Route::get('/Department/List', [Department::class, 'departmentList'])->name('DepartmentList');
    Route::get('/Department/Get/title/{id}', [Department::class, 'getTitleDetail'])->name('NewsUpdatesget');
    Route::post('/Edit-Department/title/{id}', [Department::class, 'editDepartmentTitle'])->name('EditDepartment');
    Route::get('/Edit-Department/title/{id}', [Department::class, 'editDepartmentTitle'])->name('EditDepartment');
    Route::get('/Edit-Department/Get/Details/{id}', [Department::class, 'getDepartmentsDetails'])->name('DepartmentDetailsParagraphOreder');
    Route::post('/Edit-Department/Add/Details', [Department::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::get('/Edit-Department/Details/List/{id}', [Department::class, 'getAllDetails'])->name('DepartmentDetailsList');
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    // -----------------------------------Committee Group
    Route::get('/Create/Committee/Group/Page', [CommitteeGroup::class, 'createGroupPage'])->name('CommitteeGroupPage');
    Route::get('/Committee/Group/List/Page', [CommitteeGroup::class, 'groupListPAge'])->name('CommitteeGroupPageListPage');
    Route::post('/Create/Committee/Group', [CommitteeGroup::class, 'createCommitteeGroups'])->name('CommitteeGroupCreate');
    Route::get('/Committee/Group/List', [CommitteeGroup::class, 'committeeList'])->name('CommitteeGroupList');
    Route::get('/Committee/Group/Select/{id}', [CommitteeGroup::class, 'selectIdWise'])->name('CommitteeGroupSelectIDWise');
    Route::put('/Edit/Committee/Group/{id}', [CommitteeGroup::class, 'editCommitteeGroup'])->name('CommitteeGroupEdit');
    Route::patch('/Committee/Group/EditStatus/{id}', [CommitteeGroup::class, 'statusEdit'])->name('CommitteeGroupStsEdit');
    Route::get('/Committee/Group/Active/Status/List', [CommitteeGroup::class, 'activeGroupList'])->name('FacilityGroupStatusList');
    Route::delete('/Delete/Committee/Group/{id}', [CommitteeGroup::class, 'removeCommittees'])->name('CommitteeGroupRemove');

    // --------------------------------------Committee
    Route::get('/Create/Committee/Page', [Committee::class, 'committeePage'])->name('CreateCommitteePage');
    Route::get('/Committee/List/Page', [Committee::class, 'CommitteeListPage'])->name('CommitteeListPage');
    Route::get('/Edit/Committee/Page/{id}', [Committee::class, 'editCommitteePage'])->name('EditCommittee');
    Route::get('/Committee/Add/Details/Page/{id}', [Committee::class, 'addMoreDetailsPage'])->name('CommitteeDetailsAddPage');
    Route::get('/View/Committee/Page/{id}', [Committee::class, 'viewcommitteePage'])->name('ViewCommitteePAge');
    Route::get('/View/Committee/single/Details/Page/{id}/{paragraphid}', [Committee::class, 'getViewSingleDetails'])->name('GetSingleCommitteeDetailsPage');

    Route::post('/Create/Committee', [Committee::class, 'createCommittee'])->name('CreateCommittee');
    Route::get('/Committee/List', [Committee::class, 'CommitteeList'])->name('CommitteeList');
    Route::get('/Committee/Select/{id}', [Committee::class, 'selectIdWise'])->name('SelectIdWise');
    Route::post('/Edit/Committee/{id}', [Committee::class, 'editCommitteeTitle'])->name('EditCommittee');
    Route::delete('/Delete/Committee/{id}', [Committee::class, 'destroy']); // Delete a specific update
    Route::delete('/Delete/Committee/Details/{id}', [Committee::class, 'deleteContent']); // Delete a specific update
    Route::post('/Committee/Add/Details', [Committee::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::get('/Committee/Get/Details/{id}', [Committee::class, 'getCommitteeDetails'])->name('GetDetails');
    Route::get('/Committee/Details/List/{id}', [Committee::class, 'getAllDetails'])->name('CommitteeDetailsList'); // ---------------------------------------------------------------------------------------------

    //---------------------------------------------------------------Course Group pages-------------------------------------------//
    Route::get('/Course-Group/Create', [CourseGroups::class, 'CreateGroupPage'])->name('CourseGroupPage');
    Route::get('/Course-Group/List/Page', [CourseGroups::class, 'CoursegroupList'])->name('CourseGroupPageListPage');
    //-------------------------------------api codes-----------------------------------------------
    Route::post('/Course-Group/Add', [CourseGroups::class, 'addCourseGroups'])->name('CourseGroupAdd');
    Route::get('/Course-Group/List', [CourseGroups::class, 'CourseList'])->name('CourseGroupList');
    Route::get('/Course-Group/List/Latest', [CourseGroups::class, 'CourseListLatest'])->name('CourseGroupListLatest');
    Route::put('/Course-Group/Edit', [CourseGroups::class, 'editCourseGroup'])->name('CourseGroupEdit');
    Route::get('/Course-Group/Select/{id}', [CourseGroups::class, 'groupDetail'])->name('CourseGroupDetail');
    Route::patch('/Course-Group/EditStatus/{id}', [CourseGroups::class, 'statusEdit'])->name('CourseGroupStsEdit');
    Route::delete('/Course-Group/Remove/{id}', [CourseGroups::class, 'removeUpdates'])->name('CourseGroupRemove');
    Route::get('/Course-Group/StatuswiseList', [CourseGroups::class, 'activeGroupList'])->name('CourseGroupStatusList');
    //----------------------------------------------------Course pages------------------------------------------------------------------//
    Route::get('/Create-Course', [Course::class, 'CreatePage'])->name('createCoursePage');
    Route::get('/Course-list/Page', [Course::class, 'CourseListPage'])->name('CourseListPage');
    Route::get('/Course-Details/Page/{id}', [Course::class, 'addMoreDetails'])->name('CourseDetailsPage');
    Route::get('/Edit-Course/{id}', [Course::class, 'CourseUpdatePage'])->name('EditCoursePage');
    Route::get('/View-Course/Page/{id}', [Course::class, 'ViewCoursePage'])->name('ViewCoursePage');
    Route::get('/View-Course/single/Details/Page/{id}/{paragraphid}', [Course::class, 'getViewSingleDetails'])->name('GetSingleCourseDetailsPage');
    //--------------------codes--------------------------------------------
    Route::post('/Create-Course/Add', [Course::class, 'createCourse'])->name('CreateCourse');
    Route::delete('/Delete/Course/{id}', [Course::class, 'destroy']); // Delete a specific Course
    Route::delete('/Delete/Course/Details/{id}', [Course::class, 'deleteContent']); // Delete a specific Course Content
    Route::get('/Course/List', [Course::class, 'CourseList'])->name('CourseList');
    Route::get('/Course/Get/Class/{id}', [Course::class, 'getTitleDetail'])->name('Courseelect');
    Route::post('/Edit-Course/Class/{id}', [Course::class, 'editCourseHeading'])->name('EditCourse');
    Route::get('/Edit-Course/Get/Details/{id}', [Course::class, 'getCourseDetails'])->name('GetCourseParagraphOrderDetails');
    Route::post('/Edit-Course/Add/Details', [Course::class, 'addMultiDetails'])->name('AddMultiDetails');
    Route::get('/Edit-Course/Details/List/{id}', [Course::class, 'getAllDetails'])->name('CourseDetailsList');


    //---------------------------------------------------------Manage Achivers----------------------------------------------//
    Route::get('/Manage-Achivers/Page', [ManageAchievers::class, 'getAchiversPage'])->name('getAchiversPage');
    Route::get('/Manage-Achivers/Update/Page/{id}', [ManageAchievers::class, 'AchiversUpdatePage'])->name('AchiversUpdatePage');
    Route::get('/Manage-Achivers/List/Page', [ManageAchievers::class, 'AchiversListPage'])->name('AchiversListPage');
    Route::post('/Manage-Achivers/Add', [ManageAchievers::class, 'addAchivers'])->name('AddManageAchivers');
    Route::get('/Manage-Achivers/List', [ManageAchievers::class, 'achiversList'])->name('ManageAchiversList');
    Route::get('/Manage-Achivers/List/latest', [ManageAchievers::class, 'achiversListlatest'])->name('ManageAchiversListlatest');
    Route::get('/Manage-Achivers/Select/{id}', [ManageAchievers::class, 'achiversDetails'])->name('ManageAchiversSelect');
    Route::put('/Manage-Achivers/Update/{id}', [ManageAchievers::class, 'updateAchivers'])->name('updateManageAchivers');
    Route::delete('/Manage-Achivers/Remove/{id}', [ManageAchievers::class, 'deleteAchivers'])->name('deleteManageAchivers');
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    //------------------------------------Manage News---------------------------//
    Route::get('/Scrooling-News/Page', [ManageNews::class, 'ScrollingNewsPage'])->name('ScrollingNewsCreatePage');
    Route::get('/News/List', [ManageNews::class, 'NewsListPage'])->name('NewsListPage');
    Route::get('/Manage-News/Update/Page/{id}', [ManageNews::class, 'NewsUpdatePage'])->name('NewsUpdatePage');
    Route::get('/View-News/Detail/{id}', [ManageNews::class, 'NewsDetailPage'])->name('ScrollingNewsDetail');
    Route::post('/Scrooling-News/Add', [ManageNews::class, 'createScrollingNews'])->name('ScrollingNewsCreate');
    Route::get('/Scrooling-News/List', [ManageNews::class, 'newslist'])->name('ScrollingNewsList');
    Route::get('/Scrooling-News/get/{id}', [ManageNews::class, 'newsDetails'])->name('ScrollingNewsSelect');
    Route::put('/Scrooling-News/Update/{id}', [ManageNews::class, 'updateNewsDetails'])->name('ScrollingNewsUpdate');
    Route::delete('/Scrooling-News/Remove/{id}', [ManageNews::class, 'newsDelete'])->name('ScrollingNewsRemove');
    Route::patch('/Scrooling-News/status/{id}', [ManageNews::class, 'statusEdit'])->name('ScrollingNewsEditStatus');

    //-------------------------------------Manage Inquiry-----------------------//
    Route::get('/Create-Contact/Page', [ManageInquiry::class, 'CreateContactPage'])->name('CreateContactPage');
    Route::get('/Manage-ContactUs/Page', [ManageInquiry::class, 'GetContactpage'])->name('ContactListpage');
    Route::get('/Manage-Contact/Update/Page/{id}', [ManageInquiry::class, 'ContactUpdatePage'])->name('ContactUpdatePage');
    Route::post('Create/Contact/Add', [ManageInquiry::class, 'CreateContact'])->name('ManageCreateContact');
    Route::get('/Manage-Contact/List', [ManageInquiry::class, 'contactList'])->name('contactList');
    Route::get('/Manage-Contact/Select/{id}', [ManageInquiry::class, 'contactInfo'])->name('contactSelect');
    Route::delete('/Manage-Contact/Delete/{id}', [ManageInquiry::class, 'deleteContact'])->name('deleteContact');
    Route::patch('EditStatus/{id}', [ManageInquiry::class, 'statusEdit'])->name('EditContactStatus');
    Route::put('/Manage-Contact/Update/{id}', [ManageInquiry::class, 'updateContact'])->name('UpdateContact');

    //---------------------------------------------------------------FAQ Master----------------------------------------------//
    Route::get('/Manage-FAQ', [FAQController::class, 'FAQPage'])->name('FAQmasterPage');
    Route::get('/List-FAQ', [FAQController::class, 'FAQListPage'])->name('FAQmasterListPage');
    Route::post('/Manage-FAQ/Add/New', [FAQController::class, 'addFAQ']);
    Route::get('/Manage-FAQ/List', [FAQController::class, 'faqList'])->name('FAQMaster.FaqList');
    Route::get('/Manage-FAQ/Select/{id}', [FAQController::class, 'faqDetail'])->name('FAQMaster.fAQDetail');
    Route::put('/Manage-FAQ/Edit', [FAQController::class, 'editFAQ'])->name('FAQMaster.UpdateFAQ');
    Route::patch('/Manage-FAQ/EditStatus/{faq_id}', [FAQController::class, 'statusEdit'])->name('FAQMaster.FAQStatus');
    Route::delete('/Manage-FAQ/Remove/{id}', [FAQController::class, 'removeFAQ'])->name('FAQMaster.RemoveFAQ');

    //---------------------------------------------------------------Designation Master----------------------------------------------//
    Route::get('/Designation-Master', [DesignationMasterController::class, 'getPage'])->name('DesignationMaster');
    Route::post('/designation-Master/Add', [DesignationMasterController::class, 'addDesignation'])->name('DesignAdd');
    Route::get('/designation-Master/List', [DesignationMasterController::class, 'designationsList'])->name('DesignList');
    Route::put('/designation-Master/Edit', [DesignationMasterController::class, 'editDesignation'])->name('DesignEdit');
    Route::get('/designation-Master/Select/{id}', [DesignationMasterController::class, 'designationsDetail'])->name('DesignDetail');
    Route::get('/designation-Master/StatusData', [DesignationMasterController::class, 'selectstatusData'])->name('SlectstatusData');
    Route::patch('/designation-Master/EditStatus/{id}', [DesignationMasterController::class, 'statusEdit'])->name('DesignStsEdit');
    Route::delete('/designation-Master/Remove/{id}', [DesignationMasterController::class, 'removeDesignations'])->name('DesignRemove');

    //----------------------------------- Massege Route-------------------------------------------
    Route::get('/Create-Massege', [ManageMassage::class, 'CreateMassegePage'])->name('CreateMassege');
    Route::get('/Message-List', [ManageMassage::class, 'messageListPage'])->name('MassageList');
    Route::get('/Message-List/all', [ManageMassage::class, 'getall'])->name('getAll');
    Route::get('/Message-List/select/{id}', [ManageMassage::class, 'selectMeassage'])->name('selectMeassage');
    Route::get('/View-Massege/{id}', [ManageMassage::class, 'ViewMassegePage'])->name('ViewMassege');
    Route::get('/ProfileList/DesignationWise', [ManageMassage::class, 'getProfileDesignationWise'])->name('getProfileDesignationWise');
    Route::post('Manage-Message/Add', [ManageMassage::class, 'createMessage'])->name('CreateMessageAdd');
    Route::get('/User/Designation/List', [ManageMassage::class, 'getUserDesignation'])->name('GetUserDesignationList');
    Route::get('/User/Department/List', [ManageMassage::class, 'getUserDepartment'])->name('GetUserDepartmentList');
    Route::get('/Message-Update-Page/{id}', [ManageMassage::class, 'updateMessagePage'])->name('updateMessagePage');
    Route::put('/Message-Update/{id}', [ManageMassage::class, 'messageUpdate'])->name('MessageUpdate');
    Route::delete('/Message-Remove/{id}', [ManageMassage::class, 'removeMessage'])->name('RemoveMessage');


    // ------------------------Manage Album routes ----------------------------------
    Route::get('/Create-Album', [ManageAlbum::class, 'CreateGalleryPage'])->name('CreateGallery');
    Route::post('/Create-Album/Add', [ManageAlbum::class, 'createAlbum'])->name('CreateAlbum');
    Route::get('/Album-List/Page', [ManageAlbum::class, 'GalleryListPage'])->name('GalleryListPage');
    Route::get('/Album/List', [ManageAlbum::class, 'albumList'])->name('GalleryList');
    Route::get('/Album-Content/{id}', [ManageAlbum::class, 'GalleryContentPage'])->name('GalleryContent');
    Route::get('/Edit-Album/{id}', [ManageAlbum::class, 'EditAlbumPage'])->name('EditAlbum');
    Route::get('/Edit-Album/{id}', [ManageAlbum::class, 'EditAlbumPage'])->name('EditAlbum');
    Route::get('/Album/get/{id}', [ManageAlbum::class, 'albumInfo'])->name('AlbumDetails');
    Route::put('/Album/update/{id}', [ManageAlbum::class, 'updateAlbum'])->name('AlbumDetails');
    Route::delete('/Album/Remove/{id}', [ManageAlbum::class, 'removeAlbum'])->name('RemoveAlbum');

    // ---------------------------- Manage Album Content Routes ----------------------------
    Route::get('/Album-Content', [ManageAlbumContent::class, 'getPage'])->name('AlbumContent');
    Route::post('/Album-Content/Add/{id}', [ManageAlbumContent::class, 'addImgContent'])->name('AlbumContentAdd');
    Route::get('/Album-Content/List/{id}', [ManageAlbumContent::class, 'imgContentList'])->name('AlbumContentList');
    Route::delete('/Album-Content/Remove/{id}', [ManageAlbumContent::class, 'deleteimgContent'])->name('AlbumContentDelete');
    Route::get('/Album/Content/get/{id}', [ManageAlbumContent::class, 'getContentPage'])->name('AlbumContent');
    Route::get('/Album/Content/details/{id}', [ManageAlbumContent::class, 'albumContentDetails'])->name('AlbumContentDetails');
    Route::put('/Album/Content/Update/{id}', [ManageAlbumContent::class, 'albumContentUpdate'])->name('AlbumContentUpdate');


    // ------------------------Manage Video routes ----------------------------------
    Route::get('/Create-Video', [Video::class, 'getVideoPage'])->name('CreateVideoPage');
    Route::post('/Create-Video/Add', [Video::class, 'createVideo'])->name('CreateVideo');
    Route::get('/Video-List/Page', [Video::class, 'VideoListPage'])->name('VideoListPage');
    Route::get('/Video/List', [Video::class, 'videoList'])->name('VideoList');
    Route::get('/Video-Content/{id}', [Video::class, 'VideoContentPage'])->name('VideoContent');
    Route::get('/Edit-Video/{id}', [Video::class, 'EditVideoPage'])->name('EditVideo');
    Route::get('/Video/get/{id}', [Video::class, 'videoInfo'])->name('VideoDetails');
    Route::put('/Video/update/{id}', [Video::class, 'updateVideo'])->name('VideoDetails');
    Route::delete('/Video/Remove/{id}', [Video::class, 'removeVideo'])->name('RemoveVideo');

    // ---------------------------- Manage Video Content Routes ----------------------------
    Route::get('/Video-Content', [ManageVideoContent::class, 'getPage'])->name('VideoContent');
    Route::post('/Video-Content/Add/{id}', [ManageVideoContent::class, 'addVideoContent'])->name('VideoContentAdd');
    Route::get('/Video-Content/List/{id}', [ManageVideoContent::class, 'videoContentList'])->name('VideoContentList');
    Route::delete('/Video-Content/Remove/{id}', [ManageVideoContent::class, 'deleteVideoContent'])->name('VideoContentDelete');
    Route::get('/Video/Edit-Content/get/{id}', [ManageVideoContent::class, 'getContentPage'])->name('VideoContent');
    Route::get('/Video/Content/details/{id}', [ManageVideoContent::class, 'videoContentDetails'])->name('VideoContentDetails');
    Route::put('/Video/Content/Update/{id}', [ManageVideoContent::class, 'videoContentUpdate'])->name('VideoContentUpdate');
});

require __DIR__ . '/auth.php';

Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('login');
Route::get('/email', [TenantAuthController::class, 'emailForm'])->name('emailForm');
Route::get('/resetpw', [TenantAuthController::class, 'resetpwForm'])->name('resetpwForm');
Route::post('/login', [TenantAuthController::class, 'login'])->name('login.post');
Route::get('/home', [TenantAuthController::class, 'home'])->name('home');
Route::post('/logout', [TenantAuthController::class, 'logout'])->name('logout');

Route::get('/register', [TenantAuthController::class, 'showRegisterForm'])->name('tenant.registerForm');
Route::post('/register', [TenantAuthController::class, 'register'])->name('tenant.register');



// Password Reset Routes
Route::get('/forgot-password', [TenantAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [TenantAuthController::class, 'sendResetPasswordEmail'])->name('password.email');
Route::get('/reset-password/{token}', [TenantAuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [TenantAuthController::class, 'resetPassword'])->name('password.store');
