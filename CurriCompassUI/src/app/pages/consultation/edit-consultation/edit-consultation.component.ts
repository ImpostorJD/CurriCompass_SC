import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { AuthService } from '../../../services/auth/auth.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';

@Component({
  selector: 'app-edit-consultation',
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
  templateUrl: './edit-consultation.component.html',
  styleUrl: './edit-consultation.component.css'
})
export class EditConsultationComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private fac: FormArrayControlUtilsService,
    public coursePipe: CourseFilterPipe,
    private activatedRoute: ActivatedRoute
  ){}

  private routerId: number = null!;

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
    semSubjects: this.fb.array([]),
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

    const courseid = event.target.value;
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
    return this.semConsultation.get('semSubjects') as FormArray;
  }

  isSelectedCourseFiltered(index: number): boolean {
    const selectedCourseId = parseInt(this.getReqCourseControl(index).value);
    if (typeof selectedCourseId != "number") {
      return false;
    }
    const filteredCourses = this.coursePipe.transform(this.courses, this.searchCourse);
    return typeof filteredCourses.find(course => course.subjectid === selectedCourseId) == "undefined" ? false: true;

  }

  getSelectedCourse(i: number | string) {
    if (typeof i == "string"){
      i = parseInt(i);
    }
    return this.courses.find((c:any) => c.subjectid === i);
  }

  handleSubmit(){
    console.log("Submit works");

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

  applyToAllRegular(){
    //TODO: Add regular student enlistment
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

    this.courseService.getCourses().subscribe({
      next: (res: any) => {
        this.courses = res;
      },
      error: err => console.error(err),
    });

    this.activatedRoute.params.subscribe(params => {
      this.routerId = params['id'];

      this.req.getResource('consultation/' + this.routerId, httpOptions(this.auth.getCookie('user')))
        .subscribe({
          next: (res: any) => {
            const data = res[1];
            this.semConsultation.patchValue(data);

            if(data.consultation_subjects.length > 0) {
              data.consultation_subjects.forEach((e:any, index:number) => {
                const subjectField = this.fb.group({
                  "subjectid": new FormControl(e.subjectid, [Validators.required]),
                  "description": new FormControl(e.subjects.subjectname),
                  "units": new FormControl(e.subjects.subjectcredits),
                  "time": new FormControl(e.time, [Validators.required]),
                  "days": new FormControl(e.days, [Validators.required]),
                });

                this.fac.addControl(this.semSubjects, subjectField);
                this.selectedCourses[index] = e.subjectid;
                this.currentUnits += e.subjects.subjectcredits;
              });
            }
          },
          error: error => console.error(error),
        })
    });
  }
}
