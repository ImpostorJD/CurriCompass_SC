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
  searchCourse: string = '';
  semsy: any = null;

  courseAvailability =  this.fb.group({
    subjectid: new FormControl('', [Validators.required]),
    semsyid: new FormControl('', [Validators.required]),
    time: new FormControl('', [Validators.required]),
    section: new FormControl('', [Validators.required]),
    limit: new FormControl("0", [Validators.required]),
    days: new FormControl('', [Validators.required]),
  });


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
          this.courseAvailability.get('subjectid')!.setErrors({'duplicate' :true});
        }
      }
    });


  }

  ngOnInit(){
    this.coursesService.getCourses().subscribe({
      next: (c:any) => {
        this.courseList = c;
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
