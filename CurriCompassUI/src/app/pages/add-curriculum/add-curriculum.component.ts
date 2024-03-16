import { HttpClientModule } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { RemoveInputErrorService } from '../../services/remove-input-error.service';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';

@Component({
  selector: 'app-add-curriculum',
  standalone: true,
  imports: [
    RouterLink,
    ReactiveFormsModule,
    HttpClientModule,
  ],
  providers: [
    HttpReqHandlerService,
    RemoveInputErrorService,
  ],
  templateUrl: './add-curriculum.component.html',
  styleUrl: './add-curriculum.component.css'
})
export class AddCurriculumComponent {
  constructor(
    private router: Router,
    private fb: FormBuilder,
    private req: HttpReqHandlerService,
    public rs: RemoveInputErrorService,
  ){}

  programs: any = null;
  courses: any = null;
  semesters: any = null;
  selectedCourses: Array<any> = [];

  curriculum = this.fb.group({
    programid : new FormControl(null, [Validators.required]),
    specialization : new FormControl(null),
    curriculum_subjects: this.fb.array([]),
  });

  onInputChange(event: any) {
    if (event.target.value.trim().length <= 0) {
      this.curriculum.get('specialization')!.setValue(null);
    }
  }

  get csubjectsFormArray() {
    return this.curriculum.get('curriculum_subjects') as FormArray;
  }

  popCsubjectsArray(index : number){
      this.csubjectsFormArray.removeAt(index);
  }

  courseSelected(index: number, event: any) {
    const courseid = event.target.value;
    this.selectedCourses[index] = parseInt(courseid);
  }

  isCourseSelected(courseid: number): boolean {
    return this.selectedCourses.includes(courseid);
  }

  addCsubjectsArray() {
    const csubject: any = this.fb.group({
      'subjectid' : new FormControl(null, [Validators.required]),
      'semid' : new FormControl(null, [Validators.required]),
      'year_level' : new FormControl(null, [Validators.required]),
    });
    this.csubjectsFormArray.push(csubject);
  }

  getYearLevelControl(index: number): FormControl{
    return (this.csubjectsFormArray.at(index) as FormGroup).get('year_level')! as FormControl;
  }

  getCsubjectsControl(index: number): FormControl{
    return (this.csubjectsFormArray.at(index) as FormGroup).get('subjectid')! as FormControl;
  }

  getCsemControl(index: number): FormControl{
    return (this.csubjectsFormArray.at(index) as FormGroup).get('semid')! as FormControl;
  }

  handleSubmit(){

    if(this.curriculum.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.curriculum);
      return;
    }

    if(this.csubjectsFormArray.length <= 0 ){
      return;
    }


    this.req.postResource('curriculum', this.curriculum.value, httpOptions).subscribe({
      next: (res:any) => {
        this.router.navigateByUrl('/curricula');
      },

      error: err => {
        this.curriculum.get('specialization')?.setErrors({duplicate: true});
        console.error(err)
      },
    })
  }

  ngOnInit(){
    this.req.getResource('programs', httpOptions).subscribe({
      next: (res: any) => {
        this.programs = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('subjects', httpOptions).subscribe({
      next: (res: any) => {
        this.courses = res[1];
      },
      error: err => console.error(err),
    });

    this.req.getResource('semesters', httpOptions).subscribe({
      next: (res: any) => {
        this.semesters = res[1];
      },
      error: err => console.error(err),
    })
  }
}
