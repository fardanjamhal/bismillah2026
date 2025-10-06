<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Sesi Ujian</title>
    </Head>

    <!--
    <nav class="navbar navbar-expand-lg navbar-light bg-white rounded fixed-top rounded-0 shadow-sm mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h6>{{ exam.lesson.short_name ?? exam.lesson.name }}</h6>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent1" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent1">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"> <a class="nav-link" aria-current="page" href="/user/dashboard"><i class='bx bx-home-alt me-1'></i>Home</a></li>
                    <li class="nav-item"> <Link class="nav-link" href="/logout" method="POST"><i class='bx bx-log-out-circle'></i>Logout</Link></li>
                </ul>
            </div>
        </div>
    </nav>
    -->
    
    <div v-if="$page.props.session.error" class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
            </div>
            <div class="ms-3">
                <h6 class="mb-0 text-white">Perhatian</h6>
                <div class="text-white">{{ $page.props.session.error }}</div>
            </div>
        </div>
    </div>

    
    <div style="background:#fff; display: none; justify-content: center; align-items: center; height: 100vh;" id="timeRemainingQuestion">
        <VueCountdown :time="durationStartExam" @progress="handleChangeDurationStartExam" @end="showQuestionExam" v-slot="{ seconds }">
            <h2 class="mb-0 text-center">
                <p><span style="font-size: 6vw;">{{  lastSection > 1 ? 'Persiapan Kolom ' + section : 'Ujian dimulai dari'  }}</span></p>
                <span style="font-size: 6vw;">{{ seconds }}</span>
            </h2>
        </VueCountdown>
    </div>
    

    <!--start page wrapper -->
    <div :class="{'container-fluid': lastSection > 1, 'container': lastSection <= 1}" style="margin-top: 6vh; display: none" id="questionExam">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12" :class="exam.show_question_number_navigation == 1 ? 'col-lg-8' : 'col-lg-12'">
                <div class="card">
                    <div class="card-header" :class="{'bg-default': lastSection > 1, 'bg-primary': lastSection <= 1}">
                        <div class="d-flex justify-content-between">
                            <div class="text-start">
                                <div v-if="exam.show_question_number == 1">
                                    <h4 class="mb-0" :class="{'text-dark': lastSection > 1, 'text-white': lastSection <= 1}">No. {{ questionLists[indexPage]['navigation_order'] }}</h4>
                                </div>
                            </div>
                            <div class="text-end">
                                <VueCountdown :transform="transform" :time="duration" @progress="handleChangeDuration" @end="handeCountdownEnd" v-slot="{ hours, minutes, seconds }">
                                    <h4 class="mb-0" :class="{'text-dark': lastSection > 1, 'text-white': lastSection <= 1}">
                                        <i class="fadeIn animated bx bx-time-five"></i>
                                        <span v-if="exam.duration >= 60"><i class="bx bx-"></i> {{ hours }} : {{ minutes }} : {{ seconds }}</span>
                                        <span v-else><i class="bx bx-"></i> {{ minutes }} : {{ seconds }}</span>
                                    </h4>
                                </VueCountdown>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="questionLists[indexPage]">
                            <div>
                                <div v-html="questionLists[indexPage]['question']" class="prevent-select clearfix ck-content"></div>
                            </div>
                            <br>
                            <table>
                                <tbody v-if="exam.type_option == 1">
                                    <tr v-for="(answer, index) in (questionLists[indexPage]['answer_order'].split(','))" :key="index">

                                        <span>
                                        <td width="50" style="padding: 10px;">
                                            <button
                                                v-if="getMyAnswer(questionLists[indexPage]['question_id']).includes(Number(answer))"
                                                @click="submitAnswer(questionLists[indexPage]['question_id'], answer)"
                                                class="btn btn-success text-white"
                                            >
                                                {{ options[index] }}
                                            </button>
                                            <button
                                                v-else
                                                @click="submitAnswer(questionLists[indexPage]['question_id'], answer)"
                                                class="btn btn-outline-primary w-100 no-click-effect"
                                            >
                                                {{ options[index] }}
                                            </button>
                                        </td>

                                        <td style="padding: 10px;">
                                            <p
                                                class="prevent-select ck-content"
                                                v-if="exam.show_answer == 1"
                                                v-html="questionLists[indexPage]['option_' + answer]"
                                                
                                                @click="submitAnswer(questionLists[indexPage]['question_id'], answer)"
                                                
                                                :class="getMyAnswer(questionLists[indexPage]['question_id']).includes(Number(answer)) ? 'text-success fw-bold' : ''"
                                                
                                                style="cursor: pointer; transition: color 0.2s;"
                                                
                                                @mouseover="event.currentTarget.style.color='#0d6efd'"
                                                
                                                @mouseleave="event.currentTarget.style.color=getMyAnswer(questionLists[indexPage]['question_id']).includes(Number(answer)) ? '#198754' : 'inherit'"
                                            ></p>
                                        </td>
                                        </span>
                                    
                                        
                                    </tr>
                                </tbody>
                                <tbody v-if="exam.type_option == 2">
                                    <tr v-for="(answer, index) in (questionLists[indexPage]['answer_order'].split(','))" :key="index"  v-if="exam.show_answer == 1">
                                        <td width="30">
                                            <p>{{ options[index] }}.</p>
                                        </td>
                                        <td>
                                            <p class="ck-content" v-html="questionLists[indexPage]['option_'+answer]"></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- <br> -->
                        </div>
                        <div v-else>
                            <div class="alert alert-danger border-0 shadow">
                                <i class="fa fa-exclamation-triangle"></i> Question Not Found!.
                            </div>
                        </div>
                    </div>
                    <div v-if="exam.type_option == 2">
                        <hr class="mb-1">
                        <span v-for="(answer, index) in questionLists[indexPage]['answer_order'].split(',')" :key="index">
                            <button v-if="getMyAnswer(questionLists[indexPage]['question_id']).includes(Number(answer))"  @click="submitAnswer(questionLists[indexPage]['question_id'], answer)" class="btn btn-secondary text-dark mx-lg-1 mx-md-1 mb-1 rounded-3 no-click-effect-section" style="background-color: #ccc; width:15%;">{{ options[index] }}</button>
                            <button v-else @click="submitAnswer(questionLists[indexPage]['question_id'], answer)" class="btn btn-secondary mx-lg-1 mx-md-1 mb-1 rounded-3 text-dark no-click-effect-section" style="background-color: #ccc; width:15%;">{{ options[index] }}</button>
                        </span>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center align-items-center">
                            <div class="text-start">
                                <div  v-if="exam.show_prev_next_button == 1">
                                    <button v-if="indexPage > 0" @click="prevPage()" type="button" class="btn btn-primary me-3">Sebelumnya</button>
                                    <button v-if="indexPage < Object.keys(questionLists).length - 1" @click="nextPage()" type="button" class="btn btn-success">Selanjutnya</button>
                                </div>
                            </div>
                            <div class="text-center" v-if="exam.show_question_number_navigation == 0 && exam.button_type_finish != 3" style="min-height: 35px;">
                                <div v-if="exam.button_type_finish == 1">
                                    <button v-if="section == lastSection" @click="showModalEndExam = true" class="btn btn-danger btn-md border-0 shadow w-100">Akhiri Tryout</button>
                                    <button v-else @click="endExam" class="btn btn-danger btn-md border-0 shadow w-100">Lanjut Bagian Berikutnya</button>
                                </div>
                                <div v-else>
                                    <button v-if="section == lastSection && indexPage >= Object.keys(questionLists).length - 1" @click="showModalEndExam = true" class="btn btn-danger btn-md border-0 shadow w-100">Akhiri Tryout</button>
                                    <button v-if="section < lastSection && indexPage >= Object.keys(questionLists).length - 1"  @click="endExam" class="btn btn-danger btn-md border-0 shadow w-100">Lanjut Bagian Berikutnya</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="exam.show_question_number_navigation == 1" class="col-md-12 col-sm-12 col-xs-12" :class="{ 'col-lg-4': exam.show_question_number_navigation == 1}">
                <div class="card">
                    <div class="card-header text-white bg-primary">
                        <div class="d-flex justify-content-center align-items-center">
                            <div>
                                <h5 class="mb-0 text-white">Navigasi Soal</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height: 480px; overflow-y: auto">
                        <div v-for="(question, index) in questionLists" :key="index">
                            <div style="width: 20%; float: left;">
                                <div style="padding: 2px;">
                                    <button @click="clickQuestion(index)" v-if="index == indexPage" class="btn btn-primary w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                    <button @click="clickQuestion(index)" v-if="index != indexPage && getMyAnswer(question.question_id) == 0" class="btn btn-light w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                    <button @click="clickQuestion(index)" v-if="index != indexPage && getMyAnswer(question.question_id) != 0" class="btn btn-success w-100"><span style="font-size:11px; font-weight: 500;">{{ question.navigation_order }}</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" style="min-height: 60px;">
                        <div v-if="exam.button_type_finish != 3">
                            <div v-if="exam.button_type_finish == 1">
                                <button v-if="section == lastSection" @click="showModalEndExam = true" class="btn btn-danger btn-md border-0 shadow w-100">Akhiri Tryout</button>
                                <button v-else @click="endExam" class="btn btn-danger btn-md border-0 shadow w-100">Lanjut Bagian Berikutnya</button>
                            </div>
                            <div v-else>
                                <button v-if="section == lastSection && indexPage >= Object.keys(questionLists).length - 1" @click="showModalEndExam = true" class="btn btn-danger btn-md border-0 shadow w-100">Akhiri Tryout</button>
                                <button v-if="section < lastSection && indexPage >= Object.keys(questionLists).length - 1"  @click="endExam" class="btn btn-danger btn-md border-0 shadow w-100">Lanjut Bagian Berikutnya</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->

    <!-- modal akhiri Tryout -->
    <div v-if="showModalEndExam" class="modal fade" :class="{ 'show': showModalEndExam }" tabindex="-1" aria-hidden="true" style="display:block;" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Akhiri Tryout ?</h5>
                </div>
                <div class="modal-body">
                    Setelah mengakhiri Tryout, Anda tidak dapat kembali ke Tryout ini lagi. Yakin akan mengakhiri Tryout?
                </div>
                <div class="modal-footer">
                    <button @click="endExam" type="button" class="btn btn-primary">Ya, Akhiri</button>
                    <button @click="showModalEndExam = false" type="button" class="btn btn-secondary">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/LayoutUser.vue';

    import MathJax, { initMathJax, renderByMathjax } from 'mathjax-vue3'

    import axios from 'axios';
    
    import {
        Head,
        Link
    } from '@inertiajs/inertia-vue3';

    //import ref
    import {
        ref
    } from 'vue';

    //import VueCountdown
    import VueCountdown from '@chenfengyuan/vue-countdown';

    //import inertia adapter
    import {
        Inertia
    } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutUser,

        //register components
        components: {
            Head,
            Link,
            VueCountdown,
            MathJax
        },

        //props
        props: {
            id: String,
            page: Number,
            exam: Object,
            setting: Object,
            duration: Object,
            questionLists: Object,
            section: Number,
            lastSection: Number,
            grade: Object,
            indexPage: Object,
        },
        mounted() {
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', '/assets/js/MathJax.js?config=TeX-AMS-MML_HTMLorMML')
            document.head.appendChild(recaptchaScript)

            function onMathJaxReady() {
                const el = document.getElementById('elementId')
                renderByMathjax(el)
            }
            initMathJax({}, onMathJaxReady);
        },

        setup(props) {            
            var indexPage;

            // Cek apakah localStorage mengandung 'examId' atau 'userId' yang cocok, jika tidak maka set ke 0
            var storedIndexPage = 
                props.exam.id != localStorage.getItem("examId") || 
                props.grade.user_id != localStorage.getItem("userId") || 
                localStorage.getItem("indexPage") === null ? 
                0 : parseInt(localStorage.getItem("indexPage"));

            // Set nilai ke indexPage
            indexPage = ref(storedIndexPage);

            // Jika localStorage tidak memiliki 'indexPage', set ke 0
            if (localStorage.getItem("indexPage") === null) {
                localStorage.setItem("indexPage", 0);
            }

            let options = ["A", "B", "C", "D", "E"];

            if(props.exam.id != localStorage.getItem("examId") || props.grade.user_id != localStorage.getItem("userId")) {
                localStorage.setItem("examId", props.exam.id);
                localStorage.setItem("userId", props.grade.user_id);
                localStorage.setItem("myAnswers", []);
            }

            const storedArray = localStorage.getItem("myAnswers");
            let myAnswers = storedArray ? JSON.parse(storedArray) : [];
            
            localStorage.setItem("myAnswers", JSON.stringify(myAnswers));
            localStorage.setItem("examId", props.exam.id);
            const counter = ref(0);

            const duration = ref(props.duration);
            const durationStartExam = ref(0);

            const handleChangeDuration = (() => {
                duration.value = duration.value - 1000;
                counter.value = counter.value + 1;
            });

            const handleChangeDurationStartExam = (() => {
                durationStartExam.value = durationStartExam.value - 1000;
            });

            const loadMath = (() => {
                let recaptchaScript = document.createElement('script')
                recaptchaScript.setAttribute('src', '/assets/js/MathJax.js?config=TeX-AMS-MML_HTMLorMML')
                document.head.appendChild(recaptchaScript)

                function onMathJaxReady() {
                    const el = document.getElementById('elementId')
                    renderByMathjax(el)
                }
                initMathJax({}, onMathJaxReady);
            });

            const prevPage = (() => {
                indexPage.value = parseInt(indexPage.value) - 1;
                loadMath();
            });

            const nextPage = (() => {
                indexPage.value = parseInt(indexPage.value) + 1;
                loadMath();
            });

            const clickQuestion = ((index) => {
                indexPage.value = index;
                loadMath();
            });

            const confirm = (() => {
                if(props.setting.block_system_type == 1) {
                    if(props.grade.is_blocked != 1 && props.exam.total_tolerance) {
                        let total_tolerance = parseInt(props.grade.total_tolerance) > 0 ? parseInt(props.grade.total_tolerance) - 1 : 0;
                        let tolerance = total_tolerance == 0 ? 'Tolerasi habis, ' : 'Toleransi Tersisa '+ total_tolerance +' kali lagi, ';
                        Swal.fire({
                            title: 'UJIAN AKAN DI DIBLOKIR JIKA ANDA MENINGGALKAN SESI UJIAN',
                            text: tolerance +" jika toleransi habis anda tidak dapat melanjutkan ujian dan harus menghubungi admin",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Mengerti',
                            allowOutsideClick: false
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                Inertia.post(`/user/exams/${props.exam.id}/decrement-tolerance`, {
                                    grade_id: props.grade.id,
                                },{
                                    onSuccess: () => {
                                        if(total_tolerance == 0) {
                                            location.reload();
                                        }
                                    },
                                });  
                            }
                        })
                    }
                } else if(props.setting.block_system_type == 2) {
                    Swal.fire({
                        title: 'Peringatan',
                        text: "Hindari Membuka Tab atau Aplikasi Lain Selama Ujian. Fokuslah Pada Pertanyaan Selama Ujian Berlangsung.",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Mengerti',
                        allowOutsideClick: false
                    })
                }
            });

            const getMyAnswer = ((question_id) => {
                const item = myAnswers.find(item => item.question_id === question_id);
                return item ? item.answer : [];
            });

            let answeredQuestionsCount = 0;

            const submitAnswer = (question_id, answer) => {
                const numericAnswer = Number(answer);
                const index = myAnswers.findIndex(item => item.question_id === question_id);
                const limitAnswer = props.questionLists[indexPage.value]['total_answer_limit'];
                
                if (index !== -1) {
                    if (Array.isArray(myAnswers[index].answer)) {
                        // Jika limit hanya satu jawaban, ganti dengan jawaban baru
                        if (limitAnswer === 1) {
                            myAnswers[index].answer = [numericAnswer];
                        } else {
                            if (myAnswers[index].answer.includes(numericAnswer)) {
                                // Jika jawaban sudah ada, hapus dari array
                                myAnswers[index].answer = myAnswers[index].answer.filter(ans => ans !== numericAnswer);
                            } else if (myAnswers[index].answer.length < limitAnswer) {
                                // Tambahkan jawaban baru dan urutkan
                                myAnswers[index].answer.push(numericAnswer);
                                myAnswers[index].answer.sort((a, b) => a - b); // Mengurutkan secara ascending
                            } else {
                                Swal.fire({
                                    title: 'Informasi',
                                    text: "Tidak dapat mengirim jawaban karena sudah mencapai batas. Silakan klik jawaban yang sudah ada untuk menghapusnya dan menggantinya dengan jawaban lain.",
                                    icon: 'info',
                                    showCancelButton: false,
                                    confirmButtonText: 'Mengerti',
                                    allowOutsideClick: false
                                });

                                return false;
                            }
                        }
                    } else {
                        // Jika jawaban bukan array, buat jadi array dengan jawaban baru
                        myAnswers[index].answer = [numericAnswer];
                    }
                } else {
                    // Jika belum ada jawaban, tambahkan dengan jawaban baru
                    myAnswers.push({ question_id: question_id, answer: [numericAnswer] });
                }

                try {
                    if (answeredQuestionsCount % 20 === 0) {
                        checkConnection();
                    }

                    localStorage.setItem("myAnswers", JSON.stringify(myAnswers));

                    answeredQuestionsCount++;

                    if (indexPage.value < Object.keys(props.questionLists).length - 1) {
                        if (props.exam.next_question_automatically == 1 && limitAnswer == (myAnswers[index] ? myAnswers[index].answer.length : 1)) {
                            nextPage();
                        }
                    }

                    localStorage.setItem("indexPage", indexPage.value);

                    if (indexPage.value == Object.keys(props.questionLists).length - 1 && props.exam.button_type_finish == 3) {
                        Swal.fire({
                            title: 'Informasi',
                            text: "Anda sudah mengerjakan sampai soal terakhir, silakan akhiri, lanjut kebagian berikutnya atau tunggu waktu ujian hingga selesai.",
                            icon: 'info',
                            showCancelButton: false,
                            confirmButtonText: 'Mengerti',
                            allowOutsideClick: false
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'Error',
                        text: "Submit Jawaban Error, silakan lakukan refresh dan pastikan perangkat terhubungan dengan jaringan." + error,
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonText: 'Refresh',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            };

            const showModalEndExam      = ref(false);

            const checkConnection = () => {
                axios.get('/check-connection').then(response => {
                }).catch(error => {
                    Swal.fire({
                        title: 'Error',
                        text: "Submit Jawaban Error, silakan lakukan refresh dan pastikan perangkat terhubungan dengan jaringan.\n" + error,
                        icon: 'error',
                        showCancelButton: false,
                        confirmButtonText: 'Refresh',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                });
            }

            const endExam = ((block = '') => {

                checkConnection();

                if(props.section == props.lastSection || block == 'block') {
                    Inertia.post(`/user/exams/${props.exam.id}/exam-end`, {
                        exam_id: props.exam.id,
                        grade_id: props.grade.id,
                        myAnswers: myAnswers
                    },{
                        onSuccess: () => {
                            if(block == 'block') {
                                Swal.fire({
                                    title: 'UJIAN DIBLOKIR KARENA SUDAH MELEWATI BATAS TOLERANSI.',
                                    text: "Anda tidak dapat melanjutkan ujian, silakan hubungi admin",
                                    icon: 'warning',
                                    showCancelButton: false,
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Mengerti',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {      
                                

                                if(props.exam.exam_group && props.exam.exam_group.exam_group_navigation_mode == 2) {
                                    $('#questionExam').hide();
                                    localStorage.setItem("indexPage", 0);
                                    location.reload();
                                } else {
                                    Swal.fire({
                                        title: 'Success..',
                                        text: "Ujian Anda Selesai, Semoga Mendapatkan Nilai Terbaik.",
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Tutup',
                                        allowOutsideClick: false
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            localStorage.setItem("indexPage", 0);
                                            location.reload();
                                        }
                                    })
                                }
                            }
                        },
                    });
                } else {
                    $('#questionExam').hide();
                    localStorage.setItem("indexPage", 0);
                    Inertia.get(`/user/exams/${props.exam.id}/grades/${props.grade.id}/sections/${props.section + 1}?nextsection=1`);
                }
            });


            const handeCountdownEnd = () => {
                if (props.section == props.lastSection) {
                    Swal.fire({
                        title: 'Waktu Habis',
                        text: "Waktu Tryout sudah berakhir! Klik Selesai untuk mengakhiri Tryout",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'Selesai',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            endExam();
                        }
                    })
                } else {
                    endExam();
                }
            }

            if(props.setting.block_system_type != 0) {
                window.addEventListener("blur", confirm);
            }

            if(props.grade.is_blocked == 1) {
                Swal.fire({
                    title: 'Ujian di Blokir',
                    text: "Masukan Kode Untuk Membuka Blokir",
                    input: 'text',
                    icon: 'error',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: false,
                    allowOutsideClick: false,
                    confirmButtonText: 'Buka Blokir',
                    showLoaderOnConfirm: true,
                    preConfirm: (token) => {
                        axios.get(`/user/exams/${props.exam.id}/grades/${props.grade.id}/unblocked`, {
                            params: {
                                token: token
                            }
                        })
                        .then(response => {
                            if(response.data.success == true) {
                                Swal.fire({
                                    title: 'Blokir Berhasil Dibuka',
                                    text: 'Silakan Lanjut Mengerjakan Soal.',
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Oke',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            } else {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: response.data.message,
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonText: 'Ulangi',
                                    allowOutsideClick: false
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }
                        })
                        .catch(error => {
                            console.error('Request failed:', error);
                            Swal.showValidationMessage(`Request failed: ${error}`);
                        });
                    },
                })
            }

            const showQuestionExam = () => {
                axios.get(`/user/exams/${props.exam.id}/grades/${props.grade.id}/finished`)
                .then(response => {
                    if(response.data.success == true) {
                        if(response.data.is_finished == true) {
                            $('#questionExam').hide();
                            $('#timeRemainingQuestion').hide();

                            Swal.fire({
                                title: 'Ujian Sudah Selesai',
                                text: 'Anda tidak dapat kembali ke sesi ujian karena ujian sudah berakhir.',
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonText: 'Oke',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    localStorage.setItem("indexPage", 0);
                                    location.reload();
                                }
                            })
                        } else {
                            $('#questionExam').show();
                            $('#timeRemainingQuestion').hide();
                        }
                    }
                })
                .catch(error => {
                    console.error('Request failed:', error);
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            }

            return {
                indexPage,
                options,
                duration,
                durationStartExam,
                handleChangeDuration,
                handleChangeDurationStartExam,
                prevPage,
                nextPage,
                clickQuestion,
                submitAnswer,
                getMyAnswer,
                showModalEndExam,
                endExam,
                confirm,
                checkConnection,
                loadMath,

                showQuestionExam,
                handeCountdownEnd
            }
        },
        methods: {
            transform(props) {
                Object.entries(props).forEach(([key, value]) => {
                    const digits = value < 10 ? `0${value}` : value;
                    props[key] = `${digits}`;
                });

                return props;
            },
        },
    }
</script>

<style>

        @media (max-width: 720px) {
            /* Large screen size */
            .no-click-effect-section  {
                width: calc(20% - 3pt);
                margin:2px;
            }
        }
    .modal-header {
        text-align: center;
    }

    audio::-webkit-media-controls-current-time-display, -webkit-media-controls-current-time-display {
        margin-left:70px;
    }

    audio::-webkit-media-controls-timeline, -webkit-media-controls-timeline {
        display:none;
    }

    audio::-webkit-media-controls-time-remaining-display, -webkit-media-controls-time-remaining-display {
        margin-right:15px;
    }

    audio::-webkit-media-controls-play-button, -webkit-media-controls-play-button {
        /* margin-right:15px; */
        display:none;
    }

    /* audio::-webkit-media-controls-play-button, -webkit-media-controls-play-button {
        display: none;
    } */

    .prevent-select {
        -webkit-user-select: none; /* Safari */
        -ms-user-select: none; /* IE 10 and IE 11 */
        user-select: none; /* Standard syntax */
    }

    .unselectable {
        -webkit-user-select: none;
        -webkit-touch-callout: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .no-click-effect:active,

    .no-click-effect:focus {
        outline: none; 
        box-shadow: none; 
        border: none;
        background:#fff;
        border:1px solid #008cff;
        color:#008cff;
    }

    .no-click-effect-section:active,
    
    .no-click-effect-section:focus {
        outline: none; 
        box-shadow: none; 
        border: none;
        background:#fff;
        border:1px solid #51585e;
        color:#51585e;
    }

</style>