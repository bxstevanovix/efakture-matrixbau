<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li class="menu-title border-top-0 pt-2">@lang('Main Menu')</li>
            

            <li>
                <a href="/">
                    <i class="flaticon-layout text-primary"></i>
                    <span class="nav-text">@lang('Dashboard')</span>
                </a>
            </li>

            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <svg data-chakra-component="CIcon" viewBox="22.82 10.4 63.45 70.17" role="presentation" class="css-5ffdf css-3cz70w css-0"><g>
                        <path fill="currentColor" d="M63.08 80.57H30.91a8.1 8.1 0 01-8.09-8.09V54l17.37-43.6h22.89a8.08 8.08 0 018.08 8.08v54a8.09 8.09 0 01-8.08 8.09zM24.82 54.4v18.08a6.09 6.09 0 006.09 6.09h32.17a6.09 6.09 0 006.08-6.09v-54a6.09 6.09 0 00-6.08-6.08H41.54z"></path>
                        <path fill="currentColor" d="M24.77 54.53l-1.89-.65c3.65-10.72 10.44-15.14 15.9-18.69 1.35-.87 2.62-1.7 3.78-2.57 2-1.47 2.62-3 2-4.68a19.28 19.28 0 00-2.43-4.33l-1-1.58C37.19 16 39.88 11.1 40 10.9l1.73 1c-.08.16-2.18 4 1.06 9.05.37.57.71 1.09 1 1.56a21.72 21.72 0 012.65 4.77c.89 2.51-.07 5-2.71 7-1.21.91-2.51 1.75-3.89 2.65-5.44 3.5-11.64 7.53-15.07 17.6zM31.44 68.06h30.45v2H31.44zM31.25 59.59H61.7v2H31.25zM31.25 51.12H61.7v2H31.25zM70.03 56.35h9.32v2h-9.32zM69.78 49.89h9.32v2h-9.32zM69.78 43.27h9.32v2h-9.32z"></path>
                        <path fill="currentColor" d="M81.45 68.12h-12.1v-2h12.1a2.82 2.82 0 002.82-2.82V29.39A2.44 2.44 0 0081.83 27H70.4v-2h11.43a4.44 4.44 0 014.44 4.44V63.3a4.82 4.82 0 01-4.82 4.82z"></path>
                        <path fill="none" d="M0 0h90.71v90.71H0z"></path>
                        </g>
                    </svg>
                    <span class="nav-text">@lang('Izlazne fakture')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li><a class="dz-active" href="{{route('customer-invoices.index')}}">@lang('Lista fakture')</a></li>
                    <li><a class="dz-active" href="{{route('customer-invoices.reports')}}">@lang('Ukupan Izvjestaj')</a></li>
                    <li><a class="dz-active" href="{{route('customer-invoices.create')}}">+ @lang('Kreiraj fakturu')</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <svg data-chakra-component="CIcon" viewBox="0 10.1 71.79 70.17" role="presentation" class="css-5ffdf css-3cz70w css-0"><g>
                        <path fill="currentColor" d="M63.7 80.27H31.53a8.08 8.08 0 01-8.08-8.08h2a6.09 6.09 0 006.08 6.08H63.7a6.09 6.09 0 006.09-6.08v-54a6.09 6.09 0 00-6.09-6.09H42.07L25.45 41.18v2.41h-2v-2.94L40.91 10.1H63.7a8.1 8.1 0 018.09 8.09v54a8.09 8.09 0 01-8.09 8.08z"></path>
                        <path fill="currentColor" d="M23.45 50.59h2v21.6h-2zM25.35 42.17l-1.81-.86c3.7-7.76 10.51-10.93 16-13.48 1.35-.63 2.62-1.22 3.78-1.85 2.64-1.41 2.12-2.48 1.94-2.83a13.49 13.49 0 00-2.37-3L41.83 19c-3.33-3.68-2.19-7.17-1.12-8.51l1.56 1.25c-.07.1-1.94 2.62 1 5.92l1 1.12A14.9 14.9 0 0147 22.26c.59 1.18 1 3.46-2.78 5.48-1.21.66-2.51 1.26-3.89 1.9-5.41 2.54-11.6 5.42-14.98 12.53zM32.06 67.76h30.45v2H32.06zM31.87 59.3h30.45v2H31.87zM8.59 52.21H0v-2h7.92l5.76-4.32A16.66 16.66 0 0120 43l.44 1.95a14.63 14.63 0 00-5.57 2.58z"></path>
                        <path fill="currentColor" d="M24.81 72.47h-4.19a11.55 11.55 0 01-6.23-1.82l-3.47-2.21H.49v-2h11l4 2.52a9.56 9.56 0 005.16 1.51h4.19zM36.22 52.12H20.14v-2h16.08a2.54 2.54 0 100-5.08H20.14V43h16.08a4.54 4.54 0 110 9.08z"></path>
                        <path fill="none" d="M.23 0h90.71v90.71H.23z"></path>
                        </g>
                    </svg>
                    <span class="nav-text">@lang('Ulazne fakture')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li><a class="dz-active" href="{{route('supplier-invoices.index')}}">@lang('Lista fakture')</a></li>
                    <li><a class="dz-active" href="{{route('supplier-invoices.reports')}}">@lang('Ukupan Izvjestaj')</a></li>
                    <li><a class="dz-active" href="{{route('supplier-invoices.create')}}">+ @lang('Kreiraj fakturu')</a></li>
                </ul>
            </li>

            <hr>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="fas fa-building"></i>
                    <span class="nav-text">@lang('Firme')</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">@lang('Firme')</li>
                    <li><a class="dz-active" href="{{ route("firme.index") }}">@lang('Lista firmi')</a></li>
                    <li><a class="dz-active" href="{{ route('firme.create') }}">+ @lang('Kreiraj firmu')</a></li>
                </ul>
            </li>
            <hr>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="fas fa-file-invoice-dollar text-primary"></i>
                    <span class="nav-text">@lang('Racuni')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li class="nav-text-icon-toggle">Racuni</li>
                    <li><a class="dz-active" href="{{ route("rechnung.index") }}">@lang('Lista racuna')</a></li>
                    <li><a class="dz-active" href="{{ route('rechnung.index') }}?openModal=1">+ @lang('Kreiraj racun')</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="true">
                    <i class="flaticon-notes"></i>
                    <span class="nav-text">@lang('Ponude')</span>
                </a>
                <ul aria-expanded="true" class="mm-show">
                    <li class="nav-text-icon-toggle">@lang('Ponude')</li>
                    <li><a class="dz-active" href="{{ route("angebote.index") }}">@lang('Lista ponuda')</a></li>
                    <li><a class="dz-active" href="{{ route('angebote.index') }}?openModal=1">+ @lang('Kreiraj ponudu')</a></li>
                </ul>
            </li>


            {{-- <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-layout"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Dashboard</li>
                    <li><a class="dz-active" href="files/index.html">Dashboard Light</a></li>
                    <li><a class="dz-active" href="files/index-2.html">Dashboard Dark</a></li>
                    <li><a class="dz-active" href="files/wallet.html">Wallet</a></li>
                    <li><a class="dz-active" href="files/invoices.html">Invoices</a></li>
                    <li><a class="dz-active" href="files/create-invoices.html">Create Invoice</a></li>
                    <li><a class="dz-active" href="files/card-center.html">Card Center</a></li>
                    <li><a class="dz-active" href="files/transaction-details.html">Transaction</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon flaticon-user-1"></i>
                    <span class="nav-text">Profile</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="dz-active" href="files/profile/overview.html">Overview</a></li>
                    <li><a class="dz-active" href="files/profile/projects.html">Projects</a></li>
                    <li><a class="dz-active" href="files/profile/projects-details.html">Projects Details</a></li>
                    <li><a class="dz-active" href="files/profile/campaigns.html">Campaigns</a></li>
                    <li><a class="dz-active" href="files/profile/documents.html">Documents</a></li>
                    <li><a class="dz-active" href="files/profile/followers.html">Followers</a></li>
                    <li><a class="dz-active" href="files/profile/activity.html">Activity</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon flaticon-app"></i>
                    <span class="nav-text">Account</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="dz-active" href="files/account/overview.html">Overview</a></li>
                    <li><a class="dz-active" href="files/account/settings.html">Settings</a></li>
                    <li><a class="dz-active" href="files/account/security.html">Security</a></li>
                    <li><a class="dz-active" href="files/account/activity.html">Activity</a></li>
                    <li><a class="dz-active" href="files/account/billing.html">Billing</a></li>
                    <li><a class="dz-active" href="files/account/statements.html">Statements</a></li>
                    <li><a class="dz-active" href="files/account/referrals.html">Referrals</a></li>
                    <li><a class="dz-active" href="files/account/api-keys.html">Api keys</a></li>
                    <li><a class="dz-active" href="files/account/logs.html">Logs</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-web-1"></i>
                    <span class="nav-text">CMS</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">CMS</li>
                    <li><a class="dz-active" href="files/content.html">Content</a></li>
                    <li><a class="dz-active" href="files/content-add.html">Add Content</a></li>
                    <li><a class="dz-active" href="files/menu.html">Menus</a></li>
                    <li><a class="dz-active" href="files/email-template.html">Email Template</a></li>
                    <li><a class="dz-active" href="files/add-email.html">Add Email</a></li>
                    <li><a class="dz-active" href="files/blog.html">Blog</a></li>
                    <li><a class="dz-active" href="files/add-blog.html">Add Blog</a></li>
                    <li><a class="dz-active" href="files/blog-category.html">Blog Category</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-chip"></i>
                    <span class="nav-text">AIKit</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">AIKit</li>
                    <li><a class="dz-active" href="files/auto-write.html">Auto Writer</a></li>
                    <li><a class="dz-active" href="files/scheduled.html">Scheduler</a></li>
                    <li><a class="dz-active" href="files/repurpose.html">Repurpose</a></li>
                    <li><a class="dz-active" href="files/rss.html">RSS</a></li>
                    <li><a class="dz-active" href="files/chatbot.html">Chatbot</a></li>
                    <li><a class="dz-active" href="files/fine-tune-models.html">Fine-tune Models</a></li>
                    <li><a class="dz-active" href="files/prompt.html">AI Menu Prompts</a></li>
                    <li><a class="dz-active" href="files/setting.html">Settings</a></li>
                    <li><a class="dz-active" href="files/import.html">Export/Import Settings</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-blog"></i>
                    <span class="nav-text">Blog</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Blog</li>
                    <li><a class="dz-active" href="files/blog-post.html">Blog Post</a></li>
                    <li><a class="dz-active" href="files/blog-home.html">Blog Home</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-user"></i>
                    <span class="nav-text">User</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">User</li>
                    <li><a class="dz-active" href="files/app-profile.html">Profile</a></li>
                    <li><a class="dz-active" href="files/edit-profile.html">Edit Profile</a></li>
                    <li><a class="dz-active" href="files/post-details.html">Post Details</a></li>


                </ul>
            </li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-bag"></i>
                    <span class="nav-text">jobs</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">jobs</li>
                    <li><a class="dz-active" href="files/job-view.html">Job View</a></li>
                    <li><a class="dz-active" href="files/job-application.html">Job Application</a></li>
                    <li><a class="dz-active" href="files/apply-job.html">Apply Job</a></li>
                    <li><a class="dz-active" href="files/new-job.html">New Job</a></li>

                </ul>
            </li>

            <li><a href="files/pricing.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-price-tag"></i>
                    <span class="nav-text">Pricing</span>
                </a>
            </li>
            <li class="menu-title">Apps</li>
            <li>
                <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-chat"></i>
                    <span class="nav-text">Chat</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Chat</li>
                    <li><a class="dz-active" href="files/chat-home.html">Chat Home</a></li>
                    <li><a class="dz-active" href="files/chat-modal.html">Chat Modal</a></li>

                </ul>
            </li>
            <li><a href="files/kanban.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-kanban"></i>
                    <span class="nav-text">Kanban</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">

                    <i class="flaticon-email"></i>
                    <span class="nav-text">Email</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Email</li>
                    <li><a class="dz-active" href="files/email-compose.html">Compose</a></li>
                    <li><a class="dz-active" href="files/email-inbox.html">Inbox</a></li>
                    <li><a class="dz-active" href="files/email-read.html">Read</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-shopping-bag"></i>

                    <span class="nav-text">Ecommerce</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Ecommerce</li>
                    <li><a class="dz-active" href="files/ecommerce-dashboard.html">Dashboard</a></li>
                    <li><a class="dz-active" href="files/ecommerce-setting.html">Setting</a></li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Categories</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Categories</li>
                            <li><a class="dz-active" href="files/category-table.html">Category Table</a></li>
                            <li><a class="dz-active" href="files/add-categary.html">Add Category</a></li>
                            <li><a class="dz-active" href="files/edit-categary.html">Edit Category</a></li>
                        </ul>
                    </li>

                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Products</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Products</li>
                            <li><a class="dz-active" href="files/product-table.html">Product Table</a></li>
                            <li><a class="dz-active" href="files/add-product.html">Add product</a></li>
                            <li><a class="dz-active" href="files/edit-product.html">Edit Product</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Shop</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Shop</li>
                            <li><a class="dz-active" href="files/ecom-product-grid.html">Product Grid</a></li>
                            <li><a class="dz-active" href="files/ecom-product-list.html">Product List</a></li>
                            <li><a class="dz-active" href="files/ecom-product-detail.html">Product Details</a></li>
                            <li><a class="dz-active" href="files/ecom-product-order.html">Order</a></li>
                            <li><a class="dz-active" href="files/ecom-checkout.html">Checkout</a></li>
                            <li><a class="dz-active" href="files/ecom-invoice.html">Invoice</a></li>
                            <li><a class="dz-active" href="files/ecom-customers.html">Customers</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-rocket"></i>
                    <span class="nav-text">Projects</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Projects</li>
                    <li><a class="dz-active" href="files/project-list.html">Project List</a></li>
                    <li><a class="dz-active" href="files/project-card.html">Project Card</a></li>
                    <li><a class="dz-active" href="files/add-project.html">Add Project</a></li>

                </ul>
            </li>
            <li><a href="files/note.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-notes"></i>
                    <span class="nav-text">Notes</span>
                </a>
            </li>
            <li><a href="files/task.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-checkmark"></i>
                    <span class="nav-text">Task</span>
                </a>
            </li>
            <li><a href="files/file-manger.html" class="ai-icon" aria-expanded="false">
                    <i class="flaticon-approved"></i>
                    <span class="nav-text">File Manager</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-phone-book"></i>
                    <span class="nav-text">Contacts</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Contacts</li>
                    <li><a class="dz-active" href="files/contact-list.html">Contact List</a></li>
                    <li><a class="dz-active" href="files/contact-card.html">Contact Card</a></li>
                </ul>
            </li>

            <li><a href="files/app-calender.html" class="" aria-expanded="false">
                    <i class="flaticon-calendar-2"></i>
                    <span class="nav-text">Calender</span>

                </a>
            </li>
            <li class="menu-title">Components</li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-bar-chart"></i>
                    <span class="nav-text">Charts</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Charts</li>
                    <li><a class="dz-active" href="files/apex.html">Apex Chart</a></li>
                    <li><a class="dz-active" href="files/chart-flot.html">Flot</a></li>
                    <li><a class="dz-active" href="files/chart-morris.html">Morris</a></li>
                    <li><a class="dz-active" href="files/chart-chartjs.html">Chartjs</a></li>
                    <li><a class="dz-active" href="files/chart-chartist.html">Chartist</a></li>
                    <li><a class="dz-active" href="files/chart-sparkline.html">Sparkline</a></li>
                    <li><a class="dz-active" href="files/chart-peity.html">Peity</a></li>

                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-web"></i>
                    <span class="nav-text">Bootstrap</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Bootstrap</li>
                    <li><a class="dz-active" href="files/ui-accordion.html">Accordion</a></li>
                    <li><a class="dz-active" href="files/ui-alert.html">Alert</a></li>
                    <li><a class="dz-active" href="files/ui-badge.html">Badge</a></li>
                    <li><a class="dz-active" href="files/ui-button.html">Button</a></li>
                    <li><a class="dz-active" href="files/ui-modal.html">Modal</a></li>
                    <li><a class="dz-active" href="files/ui-button-group.html">Button Group</a></li>
                    <li><a class="dz-active" href="files/ui-list-group.html">List Group</a></li>
                    <li><a class="dz-active" href="files/ui-card.html">Cards</a></li>
                    <li><a class="dz-active" href="files/ui-carousel.html">Carousel</a></li>
                    <li><a class="dz-active" href="files/ui-dropdown.html">Dropdown</a></li>
                    <li><a class="dz-active" href="files/ui-popover.html">Popover</a></li>
                    <li><a class="dz-active" href="files/ui-progressbar.html">Progressbar</a></li>
                    <li><a class="dz-active" href="files/ui-tab.html">Tab</a></li>
                    <li><a class="dz-active" href="files/ui-typography.html">Typography</a></li>
                    <li><a class="dz-active" href="files/ui-pagination.html">Pagination</a></li>
                    <li><a class="dz-active" href="files/ui-grid.html">Grid</a></li>

                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-puzzle"></i>
                    <span class="nav-text">Plugins</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Plugins</li>
                    <li><a class="dz-active" href="files/uc-select2.html">Select 2</a></li>
                    <li><a class="dz-active" href="files/uc-nestable.html">Nestedable</a></li>
                    <li><a class="dz-active" href="files/uc-noui-slider.html">Noui Slider</a></li>
                    <li><a class="dz-active" href="files/uc-tree.html">Tree View</a></li>
                    <li><a class="dz-active" href="files/uc-star-rating.html">Star Rating</a></li>
                    <li><a class="dz-active" href="files/uc-drag-and-drop.html">Drag And Drop</a></li>
                    <li><a class="dz-active" href="files/uc-media-player.html">Media Player</a></li>
                    <li><a class="dz-active" href="files/uc-sweetalert.html">Sweet Alert</a></li>
                    <li><a class="dz-active" href="files/uc-toastr.html">Toastr</a></li>
                    <li><a class="dz-active" href="files/map-jqvmap.html">Jqv Map</a></li>
                    <li><a class="dz-active" href="files/uc-lightgallery.html">Light Gallery</a></li>
                </ul>
            </li>
            <li><a href="files/widget-basic.html" class="" aria-expanded="false">
                    <i class="flaticon-app"></i>
                    <span class="nav-text">Widget</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-registration"></i>
                    <span class="nav-text">Forms</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Forms</li>
                    <li><a class="dz-active" href="files/form-element.html">Form Elements</a></li>
                    <li><a class="dz-active" href="files/form-wizard.html">Wizard</a></li>
                    <li><a class="dz-active" href="files/form-ckeditor.html">CkEditor</a></li>
                    <li><a class="dz-active" href="files/form-pickers.html">Pickers</a></li>
                    <li><a class="dz-active" href="files/form-validation.html">Form Validate</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-grid"></i>
                    <span class="nav-text">Table</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li class="nav-text-icon-toggle">Table</li>
                    <li><a class="dz-active" href="files/table-bootstrap-basic.html">Bootstrap</a></li>
                    <li><a class="dz-active" href="files/table-datatable-basic.html">Datatable</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-file"></i>
                    <span class="nav-text">Pages</span>
                </a>
                <ul aria-expanded="false" class="left">
                    <li><a class="dz-active" href="files/page-login.html">Login</a></li>
                    <li><a class="dz-active" href="files/page-register.html">Register</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                        <ul aria-expanded="false" class="left">
                            <li class="nav-text-icon-toggle">Pages</li>
                            <li><a class="dz-active" href="files/page-error-400.html">Error 400</a></li>
                            <li><a class="dz-active" href="files/page-error-403.html">Error 403</a></li>
                            <li><a class="dz-active" href="files/page-error-404.html">Error 404</a></li>
                            <li><a class="dz-active" href="files/page-error-500.html">Error 500</a></li>
                            <li><a class="dz-active" href="files/page-error-503.html">Error 503</a></li>
                        </ul>
                    </li>
                    <li><a class="dz-active" href="files/page-lock-screen.html">Lock Screen</a></li>
                    <li><a class="dz-active" href="files/empty-page.html">Empty Page</a></li>
                </ul>
            </li> --}}

        </ul>

        <div style="display: none;" class="copyright">
            <p><strong>Kubayar Invoicing Admin Dashboard</strong> © <span class="current-year">2024</span> All
                Rights Reserved</p>
            <p class="fs-12">Made with <span class="heart"></span> by DexignZone</p>
        </div>
    </div>
</div>