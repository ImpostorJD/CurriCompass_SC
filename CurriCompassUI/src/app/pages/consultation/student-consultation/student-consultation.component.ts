import { Component, inject } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CourseAvailableFilterPipe } from '../../../services/filter/search-filters/course-available-filter.pipe';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { RemoveInputErrorService } from '../../../services/remove-input-error.service';
import { httpOptions } from '../../../../configs/Constants';
import { FormatDateService } from '../../../services/format/format-date.service';
import { CommonModule, NgClass } from '@angular/common';
import { SystemLoadingService } from '../../../services/system-loading.service';
import { LoadingComponentComponent } from '../../../components/loading-component/loading-component.component';

@Component({
  selector: 'app-student-consultation',
  standalone: true,
  imports: [
    RouterLink,
    CourseAvailableFilterPipe,
    FormsModule,
    ReactiveFormsModule,
    CommonModule,
    LoadingComponentComponent
  ],providers:[
    CourseAvailableFilterPipe
  ],
  templateUrl: './student-consultation.component.html',
  styleUrl: './student-consultation.component.css'
})
export class StudentConsultationComponent {
  constructor(
    private activatedRoute: ActivatedRoute,
    public dateformat: FormatDateService,
    private router: Router,
    private fb: FormBuilder,
    private coursePipe: CourseAvailableFilterPipe,
    private fac: FormArrayControlUtilsService,
    public rs: RemoveInputErrorService,
    public loading: SystemLoadingService,
  ){}

  searchCourse = "";
  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  private routeId = "";
  disableEnlistment = false;
  courses: any = null; //course availability
  courseSelection:any = [];
  selectedCourses: Array<any> = [];
  studentSelected:any = null;
  currentSemSy: any = null;
  currentUnits = 0;
  subjectNotTaken = 0;
  enlistedSlot:any = {};
  showError = false;
  gradeEncode = false;
  message = '';

  private time_range: any  = {
    '8-11' : ['8-10', '8-11', '9-11', '10-12'],
    '11-2' : ['10-12', '11-2', '1-3'],
    '2-5' : ['1-3','2-4','2-5', '3-5'],
  };

  userEnlistment = this.fb.group({
    enlistmentId: new FormControl(null),
    subjects: this.fb.array([])
  });

  get reqCourseArray() {
    return this.userEnlistment.get('subjects') as FormArray;
  }

  popReqCourseArray(index : number){
    this.selectedCourses.splice(index, 1)[0];
    let courseavailabilityselected = this.getReqCourseControl(index).value;

    let selected = this.studentSelected.student_record.curriculum.curriculum_subjects.find((c:any) => c.coursecode == courseavailabilityselected);

    if(selected){
      delete this.enlistedSlot[selected.coursecode];
      this.currentUnits -= parseInt(selected.units);
    }

    this.fac.popControl(this.reqCourseArray, index);
  }

  addReqCourseArray() {
    const csubject = this.fb.group({
      'coursecode' : new FormControl(null, [Validators.required]),
      'time' : new FormControl(null, [Validators.required]),
      'day' : new FormControl(null, [Validators.required]),
    });
    this.fac.addControl(this.reqCourseArray, csubject);
  }

  getReqCourseControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "coursecode");
  }


  getReqCourseTimeControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "time");
  }


  getReqCourseDayControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "day");
  }



  getReqCourseGradeControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "grade");
  }

  courseSelected(index: number) {

    const courseid = this.getReqCourseControl(index).value;
    this.selectedCourses[index] = courseid;
    //reset value
    this.enlistedSlot = {};
    this.getReqCourseDayControl(index).patchValue(null);
    this.getReqCourseTimeControl(index).patchValue(null);
    this.currentUnits = 0;
    for(const c of this.selectedCourses) {
      this.currentUnits += parseInt(this.getSubject(c).units);
    }

    this.reqCourseArray.controls.forEach(e => {
      if(e.get('time')?.value){
        this.enlistedSlot[e.get('coursecode')?.value] = [e.get('time')?.value, e.get('day')?.value];
      }
    });
  }

  isCourseSelected(courseid: string): boolean {
    const course:any = this.selectedCourses.find((c:string) =>  c === courseid);

    return (course != null && typeof course != "undefined");
  }

  isSelectedCourseFiltered(index: number): boolean {

    const filteredCourses = this.coursePipe.transform(this.courseSelection, this.searchCourse);
    return typeof filteredCourses.find(course => course === this.getReqCourseControl(index).value) == "undefined" ? false: true;

  }

  getTimeSelection(coursecode: string, day: string){
    return Array.from(new Set(this.courses
      .filter((item:any) => item.coursecode === coursecode && item.days === day)
      .map((item:any) => item.time)));
  }

  getDaySelection(coursecode: string){
    return Array.from(new Set(this.courses
      .filter((item:any) => item.coursecode === coursecode)
      .map((item:any) => item.days)));
  }

  getSectionDisplay(index: number){
    const course = this.courses.find((e:any) => e.coursecode === this.reqCourseArray.at(index).get('coursecode')?.value &&
    e.time === this.reqCourseArray.at(index).get('time')?.value &&
    e.days === this.reqCourseArray.at(index).get('day')?.value);

    if(!course) return "Not yet set";

    return course.section;
  }

  resetTimeControl(index: number){
    this.getReqCourseTimeControl(index).patchValue(null);
  }

  getUnits(index: number){
    const course = this.studentSelected.student_record.curriculum.curriculum_subjects.find((e:any) => e.coursecode === this.reqCourseArray.at(index).get('coursecode')?.value);
    if(!course) return 0;

    return course.units;
  }

  generateEnlistment(){
    this.loading.initLoading();
    this.disableEnlistment = true;
    this.req.postResource('enlistment', {"srid" : this.routeId }, httpOptions(this.auth.getCookie('user')))
      .subscribe({
        next: () => {
          this.getUser();
        },
        error: (err:any) => {
          if (err.status === 400) {

            let error_messages = err.error.status;
            this.message = error_messages;
            this.showError = true;
          }
        },
      });
  }

  resetError(){
    this.showError = false;
    this.message = "";
  }


  getUser(){
    this.reqCourseArray.clear();
    this.req.getResource('enlistment/' + this.routeId, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (data:any) => {
        this.studentSelected = data[1];
        this.currentSemSy = data[2];
        this.subjectNotTaken = data[3];
        this.setEnlistment();
      },
      error: (err:any) => {
        console.log(err);
      },
      complete: () => this.loading.endLoading(),
    });
  }

  getSubject(coursecode:string){
    let course = this.studentSelected.student_record.curriculum.curriculum_subjects.find((s:any) => s.coursecode === coursecode);
    if(course){
      return course;
    }
    return null;
  }


  setEnlistment(){

    this.currentUnits = 0;
    if(!this.studentSelected.student_record.enlistment[0]){
      this.loading.endLoading();
      return;
    }

    this.userEnlistment.get('enlistmentId')?.patchValue(this.studentSelected.student_record.enlistment[0].peid);
    this.studentSelected.student_record.enlistment[0].enlistment_subjects.forEach((data:any, i: number) => {

      let subjectselected:any = this.getSubject(data.course_availability.coursecode);
      this.selectedCourses.push(subjectselected.coursecode);
      const csubject = this.fb.group({
        'coursecode' : new FormControl(data.course_availability.coursecode, [Validators.required]),
        'day' : new FormControl(data.course_availability.days, [Validators.required]),
        'time' : new FormControl(data.course_availability.time, [Validators.required]),
      });

      this.currentUnits += parseInt(subjectselected.units);

      this.enlistedSlot[data.course_availability.coursecode] = [data.course_availability.time, data.course_availability.days];
      this.fac.addControl(this.reqCourseArray, csubject);
    });

    this.loading.endLoading();
  }

  checkSubjectOverlap(index: number, time:any) {
    let subjectOverlap = false;
    for (const [_, values] of Object.entries(this.enlistedSlot)) {
      subjectOverlap = this.isDayTimeOverlap(values, [time, this.reqCourseArray.at(index).get('day')?.value]);
      if(subjectOverlap) break;
    }

    return subjectOverlap;
  }

  private getTimeKeysForValue(value:string) {
    const keys = [];
    for (const [key, values] of Object.entries(this.time_range)) {
      if (Array.isArray(values)) {
        for (const val of values) {
          if (val == value) {
            keys.push(key);
          }
        }
      }
    }
    return keys;
  }

  addEnlistedSlot(index:number){
    this.enlistedSlot[this.reqCourseArray.at(index).get('coursecode')?.value] = [this.reqCourseArray.at(index).get('time')?.value, this.reqCourseArray.at(index).get('day')?.value];
  }

  private isDayTimeOverlap(enlistedTimeDay: any, timeDay:any) {

      let checkOverlap = this.getTimeKeysForValue(enlistedTimeDay[0]);

      let timeOverlap = false;
      timeOverlap = checkOverlap.some(t => this.time_range[t].includes(timeDay[0]));

      let dayOverlap = enlistedTimeDay[1] == timeDay[1];

      return timeOverlap && dayOverlap;
  }


  handleSubmit(){
    if(this.reqCourseArray.length == 0) {
      return;
    }

    if(this.userEnlistment.status == "INVALID"){
      return;
    }

    this.req.patchResource('enlistment/' + this.routeId,
      this.userEnlistment.value,
      httpOptions(this.auth.getCookie('user'))
    ).subscribe({
      next: () => {
        this.router.navigateByUrl('/');
      },
      error: (err:any) => {
        console.log(err);
      }
    })


  }

  ngOnInit(){

    this.loading.initLoading();
    this.activatedRoute.params.subscribe(params => {

      this.routeId = params["id"];
      this.req.getResource("course-availability/student-subject/" + this.routeId, httpOptions(this.auth.getCookie('user')))
        .subscribe({
          next: (data: any) => {
            this.courses = data[1];
            this.courseSelection = Array.from(new Set(this.courses.map((item:any) => item.coursecode)));

            this.getUser()
          },
          error: console.error,
          complete: () => this.loading.endLoading()
        })
    });


  }
}
