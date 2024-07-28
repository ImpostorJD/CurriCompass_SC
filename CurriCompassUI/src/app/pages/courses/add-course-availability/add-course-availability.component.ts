import { CommonModule } from '@angular/common';
import { Component, inject } from '@angular/core';
import { FormBuilder, FormControl, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { CourseFilterPipe } from '../../../services/filter/search-filters/course-pipe.pipe';
import { CoursesServiceService } from '../../../services/courses-service.service';
import { FormArrayControlUtilsService } from '../../../services/form-array-control-utils.service';
import { HttpReqHandlerService } from '../../../services/http-req-handler.service';
import { AuthService } from '../../../services/auth/auth.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../../configs/Constants';
import { FormatDateService } from '../../../services/format/format-date.service';
import { limitValidator } from '../../../services/validators/limit-validator';

@Component({
  selector: 'app-add-course-availability',
  standalone: true,
  imports: [
    RouterLink,
    ReactiveFormsModule,
    CommonModule,
    FormsModule,
    CourseFilterPipe
  ],
  providers: [
    CoursesServiceService,
    CourseFilterPipe,
  ],
  templateUrl: './add-course-availability.component.html',
  styleUrl: './add-course-availability.component.css'
})
export class AddCourseAvailabilityComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private coursesService: CoursesServiceService,
    private fac: FormArrayControlUtilsService,
    private coursePipe: CourseFilterPipe,
    public dateformat: FormatDateService,
  ){

  }
  private req: HttpReqHandlerService = inject(HttpReqHandlerService);
  private auth: AuthService = inject(AuthService);

  courseList: any = null;
  curricula: any = null;
  searchCourse: string = '';
  selectedCurricula:string|null = "";
  semsy: any = null;
  timeSlot = {
    lab: [
      {
        value: '8-11',
        text: '8:00 AM - 11:00 AM'
      },
      {
        value: '11-2',
        text: '11:00 AM - 2:00 PM'
      },
      {
        value: '2-5',
        text: '2:00 PM - 5:00 PM'
      },
    ],
    lec: [
      {
        value: '8-10',
        text: '8:00 AM - 10:00 AM'
      },
      {
        value: '9-11',
        text: '9:00 AM - 11:00 AM'
      },
      {
        value: '10-12',
        text: '10:00 AM - 12:00 PM'
      },
      {
        value: '1-3',
        text: '1:00 PM - 3:00 PM'
      },
      {
        value: '2-4',
        text: '2:00 PM - 4:00 PM'
      },
      {
        value: '3-5',
        text: '3:00 PM - 5:00 PM'
      },
    ]

  }

  courseAvailability =  this.fb.group({
    coursecode: new FormControl('', [Validators.required]),
    semsyid: new FormControl('', [Validators.required]),
    time: new FormControl('', [Validators.required]),
    lab: new FormControl(false),
    section: new FormControl('', [Validators.required]),
    section_limit: new FormControl(0, [Validators.required, limitValidator]),
    days: new FormControl('', [Validators.required]),
  });

  isLabHoursGreaterThanLecHours(): boolean {
    const subjectId = this.courseAvailability.get('coursecode')?.value;
    const course = this.courseList.find((e: any) => e.coursecode === subjectId);
    if (!course) {
      return false; // or handle the case when course is not found
    }
    let lab = course.hourslab > course.hourslec;

    if(lab){
      this.courseAvailability.get('lab')?.patchValue(true)
    }else{
      this.courseAvailability.get('lab')?.patchValue(false)
    }
    return lab;
  }

  handleCurriculaChange(){
    this.req.getResource('curriculum/' + this.selectedCurricula, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (data:any) => {
        this.courseList = data[1].curriculum_subjects;
      },
      error: (err:any) => {

      }
    });
  }
  handleSubmit(){
    if(this.semsy.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.courseAvailability);
      return;
    }

    this.req.postResource('course-availability', this.courseAvailability.value, httpOptions(this.auth.getCookie('user'))).subscribe({
      next: () => {
        this.router.navigateByUrl('/course-availability');
      },
      error: (err:any) => {
        if(err.status == 409){
          this.courseAvailability.get('coursecode')!.setErrors({'duplicate' :true});
        }
      }
    });


  }

  ngOnInit(){

    this.req.getResource('curriculum', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (s:any) => {
        this.curricula = s[1];
      },
      error: (err:any) => console.log(err),

    });

    this.req.getResource('semester-management', httpOptions(this.auth.getCookie('user'))).subscribe({
      next: (s:any) => {
        this.semsy = s[1];
      },
      error: (err:any) => console.log(err),

    });

  }
}
