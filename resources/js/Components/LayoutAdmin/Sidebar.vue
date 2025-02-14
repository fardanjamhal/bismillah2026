<template>
    <!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
                <div class="mt-2 text-center" v-if="$page.props.setting && $page.props.setting.logo">
                    <img v-bind:src="'/storage/upload_files/settings/' + $page.props.setting.logo" style="height:45px;"/>
                </div>
				<div v-else>
					<h4 class="logo-text" style="font-size:18px;">{{ $page.props.setting.app_name ?? 'Isi Setting Terlebih Dahulu' }}</h4>
				</div>
				<!-- <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div> -->
			</div>
			<!--navigation-->
            <!-- admin -->
			<ul class="metismenu" id="menu" v-if="$page.props.auth.user.level == 1 || $page.props.auth.user.level == 3">
                <li>
					<Link href="/admin/dashboard" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</Link>
				</li>
				<li class="menu-label">Manajemen</li>
				<li v-if="$page.props.auth.user.level == 1">
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-grid-alt'></i>
						</div>
						<div class="menu-title">Master Data</div>
					</a>
					<ul>
						<li>
                            <Link href="/admin/categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Kategori Peminatan
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/sub-categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Sub Kategori Peminatan
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/users" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>User
					        </Link>
                        </li>
					</ul>
				</li>
				<li v-if="$page.props.auth.user.level == 1">
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon"><i class='bx bx-folder'></i>
						</div>
						<div class="menu-title">Mata Pelajaran</div>
					</a>
					<ul>
                        <li>
                            <Link href="/admin/lesson-categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Kategori Mata Pelajaran
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/lessons" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Mata Pelajaran
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/value-category-groups" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Kategori Penilaian
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/question-titles" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Paket Soal
					        </Link>
                        </li>
					</ul>
				</li>
                <li v-if="$page.props.auth.user.level == 3">
                    <Link href="/admin/question-titles" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-folder'></i>
						</div>
						<div class="menu-title">Paket Soal</div>
					</Link>
                </li>
                <li>
                    <Link href="/admin/exams" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-file'></i>
						</div>
						<div class="menu-title">Latihan Soal</div>
					</Link>
                </li>
                <li>
                    <Link href="/admin/exam-groups" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-edit'></i>
						</div>
						<div class="menu-title">Tryout</div>
					</Link>
                </li>
                <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/announcements" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-message-square-detail'></i>
						</div>
						<div class="menu-title">Pengumuman</div>
					</Link>
                </li>
                <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/faqs" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-message-add'></i>
						</div>
						<div class="menu-title">Faq</div>
					</Link>
                </li>
                <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/news" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-news'></i>
						</div>
						<div class="menu-title">Berita</div>
					</Link>
                </li>
                <!-- <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/psychology-calculators" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-calculator'></i></div>
                        <div class="menu-title">Kalkulator Psikologi</div>
                    </Link>
                </li>  -->
                <li v-if="$page.props.auth.user.level == 1" class="menu-label">Materi</li>
                <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/modules" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-book'></i>
						</div>
						<div class="menu-title">Modul / Materi</div>
					</Link>
                </li> 
                <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/video-modules" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-video-recording'></i></div>
                        <div class="menu-title">Video Pembelajaran</div>
                    </Link>
                </li>
                <!-- <li v-if="$page.props.auth.user.level == 1">
                    <Link href="/admin/courses" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-chalkboard'></i></div>
                        <div class="menu-title">Course</div>
                    </Link>
                </li> -->
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1" class="menu-label">Transaksi</li>
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
					<Link href="/admin/member-categories">
						<div class="parent-icon"><i class='bx bx-user-plus'></i>
						</div>
						<div class="menu-title menu-clicked">Kategori Member</div>
					</Link>
				</li>
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
					<Link href="/admin/voucher-codes">
						<div class="parent-icon"><i class='bx bx-wallet-alt'></i>
						</div>
						<div class="menu-title menu-clicked">Kode Voucher Gratis</div>
					</Link>
				</li>
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
					<Link href="/admin/vouchers">
						<div class="parent-icon"><i class='bx bx-credit-card'></i>
						</div>
						<div class="menu-title menu-clicked">Paket Membership</div>
					</Link>
				</li>
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                    <Link href="/admin/transactions">
						<div class="parent-icon"><i class='bx bx-transfer'></i>
						</div>
						<div class="menu-title menu-clicked">Transaksi</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1 && $page.props.setting.enable_affiliate_feature == 1">
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-money'></i>
						</div>
						<div class="menu-title">Afiliasi</div>
					</a>
					<ul>
						<li>
                            <Link href="/admin/affiliates/commissions" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Komisi
					        </Link>
                        </li>
                        <li>
                            <Link href="/admin/affiliates/withdrawals" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Pengajuan Penarikan
					        </Link>
                        </li>
					</ul>
				</li>
                <li class="menu-label" v-if="$page.props.auth.user.level == 1">Setting</li>
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
					<Link href="/admin/payment-methods">
						<div class="parent-icon"><i class='bx bx-check-double'></i>
						</div>
						<div class="menu-title menu-clicked">Metode Pembayaran</div>
					</Link>
				</li>
                <li v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
					<Link href="/admin/banks">
						<div class="parent-icon"><i class='bx bx-credit-card-front'></i>
						</div>
						<div class="menu-title menu-clicked">Bank</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/banners">
						<div class="parent-icon"><i class='bx bx-image'></i>
						</div>
						<div class="menu-title menu-clicked">Banner</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/social-groups">
						<div class="parent-icon"><i class='bx bx-user-voice'></i>
						</div>
						<div class="menu-title menu-clicked">Grup Sosial Media</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/internet-protocol-whitelists">
						<div class="parent-icon"><i class='bx bx-desktop'></i>
						</div>
						<div class="menu-title menu-clicked">IP Whitelist</div>
					</Link>
				</li>
                <li v-if="$page.props.auth.user.level == 1">
					<Link href="/admin/settings/applications">
						<div class="parent-icon"><i class='bx bx-cog'></i>
						</div>
						<div class="menu-title menu-clicked">Setting</div>
					</Link>
				</li>
                <li class="menu-label">&nbsp;</li>
                <li class="menu-label">&nbsp;</li>
			</ul>

            <!-- user -->
            <ul class="metismenu" id="menu" v-if="$page.props.auth.user.level == 2">
                <li class="p-2" v-if="canDisplayTransactions && $page.props.setting.payment_methods.some(item => item.code === 'account_balance')">
                    <p class="text-center" :class="[$page.props.setting.sidebar_color && $page.props.setting.header_color ? 'text-white' : '']"><b>{{ $page.props.auth.user.name }}</b></p>
                    <p class="text-center" :class="[$page.props.setting.sidebar_color && $page.props.setting.header_color ? 'text-white' : '']">Saldo<br><b>Rp. {{ formatPrice($page.props.auth.user.account_balance) }}</b></p>
                    <button class="btn btn-primary radius-10 btn-sm menu-clicked" @click="topUp()">Top Up +</button>
				</li>
                <li class="px-2" v-if="$page.props.setting.social_group_mode == 1">
                   <button class="btn btn-success radius-10 btn-sm menu-clicked" @click="socialGroup()"><i class='bx bx-user-voice'></i>Grup Sosial Media</button>
				</li>
                <li class="menu-label">Navigation & Tools</li>
				<li>
					<Link href="/user/dashboard" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-home-circle'></i></div>
						<div class="menu-title">Dashboard</div>
					</Link>
				</li>
                <li>
                    <Link href="/user/announcements" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-message-square-detail'></i></div>
						<div class="menu-title">Pengumuman</div>
					</Link>
                </li>
                <!--
                <li>
                    <Link href="/user/faqs" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-message-add'></i></div>
						<div class="menu-title">Faq</div>
					</Link>
                </li>
                <li>
                    <Link href="/user/news" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-news'></i></div>
						<div class="menu-title">Berita</div>
					</Link>
                </li>
                -->
                <!-- <li>
					<Link href="/user/psychology-calculators" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-calculator'></i></div>
						<div class="menu-title">Kalkulator Psikologi</div>
					</Link>
				</li>  -->
                <li class="menu-label" v-if="$page.props.setting.free_member_access.some(item => ['question_practice', 'tryout'].includes(item.code))">Ujian</li>

                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'tryout')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'tryout'))">
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-intersect'></i></div>
						<div class="menu-title">Tryout</div>
					</a>
					<ul>
                        <li v-if="$page.props.voucherCategories.length > 1">
                            <Link href="/user/exam-groups" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Tryout
					        </Link>
                        </li>
                        <li v-else v-for="(category, index) in $page.props.voucherCategories" :key="index">
                            <Link :href="`/user/exam-groups/${category.id}/lesson-categories`" class="menu-clicked">
                            <i class="bx bx-right-arrow-alt"></i>Tryout
                            </Link>
                        </li>
                        <li>
                            <Link href="/user/exam-groups/histories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Riwayat Tryout
					        </Link>
                        </li>
					</ul>
				</li>  
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'question_practice')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'question_practice'))">
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-message-square-edit'></i></div>
						<div class="menu-title">Latihan Soal</div>
					</a>
					<ul>
						<li v-if="$page.props.voucherCategories.length > 1">
                            <Link href="/user/categories" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Latihan Soal
					        </Link>
                        </li>
                        <li v-else v-for="(category, index) in $page.props.voucherCategories" :key="index">
                            <Link :href="`/user/categories/${category.id}/lesson-categories`" class="menu-clicked">
                            <i class="bx bx-right-arrow-alt"></i>Latihan Soal
                            </Link>
                        </li>
                        <li>
                            <Link href="/user/grades" class="menu-clicked">
                               <i class="bx bx-right-arrow-alt"></i>Riwayat Latihan
					        </Link>
                        </li>
					</ul>
				</li>
                <li class="menu-label" v-if="$page.props.setting.free_member_access.some(item => ['module', 'video_module', 'course'].includes(item.code))">Materi</li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'module'))">
                    <Link href="/user/modules" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-book'></i></div>
                        <div class="menu-title">Modul / Materi</div>
                    </Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'video_module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'video_module'))">
                    <Link href="/user/video-modules" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-video-recording'></i></div>
                        <div class="menu-title">Video Pembelajaran</div>
                    </Link>
                </li>
                <li v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'course')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'course'))">
                    <Link href="/user/courses" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-chalkboard'></i></div>
                        <div class="menu-title">Course</div>
                    </Link>
                </li>
                <li class="menu-label" v-if="canDisplayMembershipTransactions || canDisplayTransactions">{{ canDisplayMembershipTransactions ? 'Beli Paket' : canDisplayTransactions ? 'Transaksi' : '' }}</li>
                <li v-if="canDisplayTransactions && canDisplayMembershipTransactions">
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-credit-card'></i></div>
                        <div class="menu-title">Paket Membership</div>
                    </a>
                    <ul>
                        <li v-for="(category, index) in $page.props.voucherCategories" :key="index">
                            <Link :href="`/user/vouchers?category_id=${category.id}`" class="menu-clicked">
                                <i class="bx bx-right-arrow-alt"></i>{{ category.name }}
                            </Link>
                        </li>
                    </ul>
                </li>
                <li v-if="canDisplayTransactions">
                    <Link href="/user/transactions" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-transfer-alt'></i></div>
                        <div class="menu-title">Riwayat Transaksi</div>
                    </Link>
                </li>
                <li v-if="$page.props.setting.enable_affiliate_feature == 1" class="menu-label">Member get Member</li>
                <li v-if="$page.props.setting.enable_affiliate_feature == 1">
                    <Link href="/user/affiliates/balances" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-user-plus'></i></div>
                        <div class="menu-title">Afiliasi</div>
                    </Link>
                </li>
                <li v-if="$page.props.setting.enable_affiliate_feature == 1">
                    <Link href="/user/affiliates/user-banks" class="menu-clicked">
                        <div class="parent-icon"><i class='bx bx-card'></i></div>
                        <div class="menu-title">Akun Bank</div>
                    </Link>
                </li>
                <li class="menu-label">Manajemen Akun</li>
                <li>
					<Link href="/user/users" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-user'></i></div>
						<div class="menu-title">Profil</div>
					</Link>
				</li>     
                <li>
					<Link href="/logout" method="POST" class="menu-clicked">
						<div class="parent-icon"><i class='bx bx-log-out'></i></div>
						<div class="menu-title">Log Out</div>
					</Link>
				</li>         
                <li class="menu-label">&nbsp;</li>
                <li class="menu-label">&nbsp;</li>
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
</template>

<script>
    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // register component
        components: {
            Link
        },
        setup() {
            const topUp = () => {
                $(".wrapper").removeClass("toggled");
                Inertia.get('/user/account-balances');
            }

            const socialGroup = () => {
                $(".wrapper").removeClass("toggled");
                Inertia.get('/user/social-groups');
            }

            return {
                topUp,
                socialGroup
            }
        },  
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        },
        computed: {
            canDisplayTransactions() {
                const user = this.$page.props.auth.user;
                const setting = this.$page.props.setting;

                return (
                    user.member_type == 2 &&
                    (
                        setting.enable_practice_question_sales == 1 ||
                        setting.enable_tryout_sales == 1 ||
                        setting.enable_module_material_sales == 1 ||
                        setting.enable_video_module_sales == 1 ||
                        setting.enable_course_sales == 1
                    )
                );
            },
            canDisplayAdminTransactions() {
                const setting = this.$page.props.setting;

                return (
                        setting.enable_practice_question_sales == 1 ||
                        setting.enable_tryout_sales == 1 ||
                        setting.enable_module_material_sales == 1 ||
                        setting.enable_video_module_sales == 1 ||
                        setting.enable_course_sales == 1
                );
            },
            canDisplayMembershipTransactions() {
                const user = this.$page.props.auth.user;
                const setting = this.$page.props.setting;

                return (
                    user.member_type == 2 &&
                    (
                        setting.practice_question_sales_type == 2 || setting.practice_question_sales_type == 3 ||
                        setting.tryout_sales_type == 2 || setting.tryout_sales_type == 3 ||
                        setting.module_material_sales_type == 2 ||   setting.module_material_sales_type == 3 ||
                        setting.video_module_sales_type == 2 || setting.video_module_sales_type == 3 ||
                        setting.course_sales_type == 2 || setting.course_sales_type == 3
                    )
                );
            }
        }
    }
</script>
