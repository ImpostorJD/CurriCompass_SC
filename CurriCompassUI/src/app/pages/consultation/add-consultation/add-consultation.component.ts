import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { Router, RouterLink } from '@angular/router';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { FormatDateService } from '../../../services/format/format-date.service';

@Component({
  selector: 'app-add-consultation',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    RouterLink,
    CourseFilterPipe,
  ],
  providers: [
    CourseFilterPipe
  ],
  templateUrl: './add-consultation.component.html',
  styleUrl: './add-consultation.component.css'
})
export class AddConsultationComponent {

  constructor(
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    public coursePipe: CourseFilterPipe,
    public dateformat: FormatDateService,
  ){}

  private auth: AuthService = inject(AuthService);
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private courseService: CoursesServiceService = inject(CoursesServiceService);

  semesters: any = null;
  schoolYears: any = null;
  curricula: any = null;
  courses: any = null;

  selectedCourses: Array<any> = [];
  currentUnits:number = 0;
  searchCourse:string = '';

  semConsultation = this.fb.group({
    cid: new FormControl(null, [Validators.required]),
    sy: new FormControl(null, [Validators.required]),
    semid: new FormControl(null, [Validators.required]),
    year_level: new FormControl(null, [Validators.required]),
    section: new FormControl(null),
    subjects: this.fb.array([]),
  });

  addSemSubject(){
    const subjectField = this.fb.group({
      "subjectid": new FormControl(null, [Validators.required]),
      "description": new FormControl(null),
      "units": new FormControl(null),
      "time": new FormControl(null, [Validators.required]),
      "days": new FormControl(null, [Validators.required]),
    });

    this.fac.addControl(this.semSubjects, subjectField);
  }

  popSemSubject(index: number){
    const courseid = this.selectedCourses.splice(index, 1);
    const selectedCourse = this.courses.find((c:any) => c.subjectid === parseInt(courseid[0]));
    if(selectedCourse){
      this.currentUnits -= selectedCourse.subjectcredits;
    }
    this.fac.popControl(this.semSubjects, index);
  }

  courseSelected(index: number, event: any) {
    const courseid = this.getReqCourseControl(index).value
    this.selectedCourses[index] = parseInt(courseid);
    const selectedCourse = this.courses.find((c:any) => c.subjectid === parseInt(courseid));
    this.getUnitsControl(index).setValue(selectedCourse.subjectcredits);
    this.getDescriptionControl(index).setValue(selectedCourse.subjectname);

    this.currentUnits += selectedCourse.subjectcredits;
  }

  isCourseSelected(courseid: number): boolean {
    const course:any = this.selectedCourses.find((c:number) =>  c === courseid);
    return (course != null && typeof course != "undefined");
  }

  getDescriptionControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.semSubjects, "description");
  }

  getUnitsControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.semSubjects, "units");
  }

  getTimeControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.semSubjects, "time");
  }

  getDaysControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.semSubjects, "days");
  }

  getReqCourseControl(index: number): FormControl{
    return this.fac.getFormControl(index, this.semSubjects, "subjectid");
  }


  get semSubjects(): FormArray{
    return this.semConsultation.get('subjects') as FormArray;
  }

  isSelectedCourseFiltered(index: number): boolean {
    const selectedCourseId = parseInt(this.getReqCourseControl(index).value);
    if (typeof selectedCourseId != "number") {
      return false;
    }
    const filteredCourses = this.coursePipe.transform(this.courses, this.searchCourse);
    return typeof filteredCourses.find(course => course.subjectid === selectedCourseId) == "undefined" ? false: true;

  }

  getCoursesAvailable(){
    if(!this.semConsultation.get('cid')?.value){
      return;
    }
    if(!this.semConsultation.get('semid')?.value){
      return;
    }
    if(!this.semConsultation.get('year_level')?.value){
      return;
    }

    this.req.postResource('course-availability', this.semConsultation.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res:any)=> {
        this.courses = [];
        this.fac.clearControls(this.semSubjects);
        this.selectedCourses = [];
        this.currentUnits = 0;
        res[1].forEach((e:any) => {
          this.courses.push(e.subjects);
        });
      },

      error: error => console.log(error),
    });
  }

  getSelectedCourse(i: number | string) {
    if (typeof i == "string"){
      i = parseInt(i);
    }
    return this.courses.find((c:any) => c.subjectid === i);
  }

  handleSubmit(){

    if(this.semConsultation.status === "INVALID"){
      markFormGroupAsDirtyAndInvalid(this.semConsultation);
      return;
    }

    this.req.postResource('consultation', this.semConsultation.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/consultation');
      },
      error: err => {
        if(err.status == 409) {
          this.semConsultation.get('cid')?.setErrors({duplicate: true});
        }
      }
    })
  }

  ngOnInit(){
    this.req.getResource('curriculum', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.curricula = res[1];
      },
      error: (err: any) => console.error(err),
    });

    this.req.getResource('school-year', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (res: any) => {
        this.schoolYears = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('semesters', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (s:any) => {
        this.semesters = s[1];
      },
      error: (err:any) => console.log(err),
     });
  }

}
