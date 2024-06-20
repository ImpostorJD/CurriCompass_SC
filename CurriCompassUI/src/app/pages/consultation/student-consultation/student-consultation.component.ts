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

@Component({
  selector: 'app-student-consultation',
  standalone: true,
  imports: [
    RouterLink,
    CourseAvailableFilterPipe,
    FormsModule,
    ReactiveFormsModule,
    CommonModule
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
    public rs: RemoveInputErrorService){

  }

  searchCourse = "";
  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);

  private routeId = "";
  disableEnlistment = false;
  courses: any = null;
  selectedCourses: Array<any> = [];
  studentSelected:any = null;
  currentSemSy: any = null;
  currentUnits = 0;
  searchCourses = "";
  subjectNotTaken = 0;
  enlistedSlot:any = {};
  showError = false;
  message = '';

  private time_range: any  = {
    '8-11' : ['8-10', '8-11', '10-12'],
    '11-2' : ['10-12', '11-2', '1-3'],
    '2-5' : ['1-3', '2-5', '3-5'],
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

    let selected = this.courses.find((c:any) => c?.caid == courseavailabilityselected);
    if(selected){
      delete this.enlistedSlot[selected.caid];
      this.currentUnits -= selected.subjects.subjectcredits;
    }

    this.fac.popControl(this.reqCourseArray, index);
  }

  addReqCourseArray() {
    const csubject = this.fb.group({
      'caid' : new FormControl(null, [Validators.required]),
      'subjectid' : new FormControl(null, [Validators.required]),
      'grade' : new FormControl(null, [Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
    });
    this.fac.addControl(this.reqCourseArray, csubject);
  }

  getReqCourseControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "caid");
  }

  getReqCourseGradeControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.reqCourseArray, "grade");
  }

  courseSelected(index: number) {

    const courseid = this.getReqCourseControl(index).value;
    this.selectedCourses[index] = parseInt(courseid);
    let subjectselected:any = this.getSubject(courseid);

    this.currentUnits = 0;
    for(const c of this.selectedCourses) {
      this.currentUnits += this.getSubject(c).subjects.subjectcredits;
    }

    this.selectedCourses[index] = parseInt(subjectselected.subjects.subjectid);
    this.enlistedSlot[courseid] = [subjectselected.time, subjectselected.days];
  }

  isCourseSelected(courseid: number): boolean {
    const course:any = this.selectedCourses.find((c:number) =>  c === courseid);
    return (course != null && typeof course != "undefined");
  }

  getSelectedCourse(i: number | string) {
    if (typeof i == "string"){
      i = parseInt(i);
    }
    let selected = this.courses.find((c:any) => c?.subjects?.subjectid === i);
    return selected;
  }

  isSelectedCourseFiltered(index: number): boolean {
    const selectedCourseId = parseInt(this.getSelectedCourse(this.getSubject(this.getReqCourseControl(index).value).subjects.subjectid).subjectid);
    if (typeof selectedCourseId != "number") {
      return false;
    }
    const filteredCourses = this.coursePipe.transform(this.courses, this.searchCourse);
    return typeof filteredCourses.find(course => course.subjectid === selectedCourseId) == "undefined" ? false: true;

  }

  generateEnlistment(){
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
    this.req.getResource('enlistment/' + this.routeId, httpOptions(this.auth.getCookie('user'))).subscribe((data:any)=>{
      this.studentSelected = data[1];
      this.currentSemSy = data[2];
      this.subjectNotTaken = data[3];
      this.setEnlistment();

    });
  }

  getSubject(caid:number){
    let course = this.courses.find((s:any) => s.caid === caid);
    if(course){
      return course;
    }
    return null;
  }

  getSubjectTakenGrade(subjectid: number) {
    let course = this.studentSelected.student_record.subjects_taken.find((s:any) => s.subjectid === subjectid);
    if(course){
      return parseFloat(course.grade);
    }
    return null;
  }

  setEnlistment(){

    this.currentUnits = 0;
    this.userEnlistment.get('enlistmentId')?.patchValue(this.studentSelected.student_record.enlistment[0].peid);
    this.studentSelected.student_record.enlistment[0].enlistment_subjects.forEach((data:any, i: number) => {

      let subjectselected:any = this.getSubject(data.caid);
      let subjectTaken:any = this.getSubjectTakenGrade(subjectselected.subjectid);

      const csubject = this.fb.group({
        'caid' : new FormControl(data.caid, [Validators.required]),
        'subjectid' : new FormControl(subjectselected.subjects.subjectid, [Validators.required]),
        'grade' : new FormControl(subjectTaken, Validators.pattern("^[0-9]+(\.?[0-9]+)?")),
      });

      this.currentUnits += subjectselected.subjects.subjectcredits;
      this.selectedCourses[i] = parseInt(subjectselected.subjects.subjectid);
      this.enlistedSlot[data.caid] = [data.course_availability.time, data.course_availability.days];
      this.fac.addControl(this.reqCourseArray, csubject);
    });
  }

  checkSubjectOverlap(caid: string) {
    let course = this.courses.find((c:any) => c.caid == caid);
    let subjectOverlap = false;

    if(!course) return false;

    for (const [key, values] of Object.entries(this.enlistedSlot)) {
      subjectOverlap = this.isDayTimeOverlap(values, [course.time, course.days]);
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

    this.activatedRoute.params.subscribe(params => {

      this.routeId = params["id"];
      this.req.getResource("course-availability/student-subject/" + this.routeId, httpOptions(this.auth.getCookie('user')))
        .subscribe({
          next: (data: any) => {
            this.courses = data[1];
            this.getUser()
          },
          error: console.error
        })
    });


  }
}
