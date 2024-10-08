            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!-- User details -->
                    <div class="user-profile text-center mt-3">

                        <div class="">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('storage/avatars/avatar.png') }}"
                                alt="{{ ucwords(auth()->user()->name) }}" class="avatar-md rounded-circle">
                        </div>
                        <div class="mt-3">
                            <h4 class="font-size-16 mb-1">{{ auth()->user()->name }}</h4>
                            <span class="text-muted"><i
                                    class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                                Online</span>
                        </div>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('invoice.index') }}" class="waves-effect">
                                    <i class="ri-wallet-line"></i>
                                    <span>My Invoice</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('support-tickets.index') }}" class="waves-effect">
                                    <i class="ri-questionnaire-line"></i>
                                    <span>My Tickets</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('downloads.index') }}" class="waves-effect">
                                    <i class="ri-download-cloud-line"></i>
                                    <span>My Downloads</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('knowledgebase.dashboard') }}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i>
                                    <span>Knowledgebase</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->
