import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { FormArrayControlUtilsService } from '../../services/form-array-control-utils.service';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { AuthService } from '../../services/auth/auth.service';
import { CommonModule } from '@angular/common';
import { markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';

@Component({
  selector: 'app-pre-requisite-modal',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './pre-requisite-modal.component.html',
  styleUrl: './pre-requisite-modal.component.css'
})
export class PreRequisiteModalComponent {
  @Output() collapseModal = new EventEmitter<boolean>();
  @Output() setCurriculumSubject = new EventEmitter<FormGroup>();
  @Input('selectedForm') selectedForm:FormGroup<any> | null = null;
  @Input('selection') selection:FormArray<any> | null = null;

  constructor(private fb: FormBuilder, private fac: FormArrayControlUtilsService, private req: HttpReqHandlerService, private auth: AuthService) {}

  preRequisite = this.fb.group({
    'year-standing': new FormControl(''),
    'courses': this.fb.array([]),
  });

  get courses(){
    return this.preRequisite.get('courses') as FormArray;
  }

  addCourse(){
    const subject: any = this.fb.group({
      'coursecode': new FormControl('', [Validators.required]),
    });

    this.fac.addControl(this.courses, subject);
  }

  popCourse(index: number) {
    this.fac.popControl(this.courses, index);
  }

  collapseModalEvent() {
    this.collapseModal.emit(true);
  }

  handleChange(index:number) {
    let found = false;
    let val = this.getCourseCodeControl(index).value;

    if(val == this.selectedForm?.get('coursecode')?.value){
      this.getCourseCodeControl(index).setErrors({'target': true});
      return;
    }
    this.selection?.controls.forEach((control) => {
      let innerControl = control.get('subjects') as FormArray;
      innerControl.controls.forEach((control) => {
        let subject = control.get('coursecode')?.value;
        if (val == subject){
          found = true;
        }
      });

    });

    if (!found) {
      this.getCourseCodeControl(index).setErrors({'nonexistent': true});
      return;
    }


  }

  getCourseCodeControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.courses, 'coursecode');
  }

  onSubmit() {
    if(this.preRequisite!.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.preRequisite!);
      return;
    }

    let year_standing_string = "";

    if (this.preRequisite.get('year-standing')!.value == "1") {
      year_standing_string = "1st YEAR STANDING";
    }else if (this.preRequisite.get('year-standing')!.value == "2") {
      year_standing_string = "2nd YEAR STANDING";
    }else if (this.preRequisite.get('year-standing')!.value == "3") {
      year_standing_string = "3rd YEAR STANDING";
    }else if (this.preRequisite.get('year-standing')!.value == "4") {
      year_standing_string = "4th YEAR STANDING";
    }else if (this.preRequisite.get('year-standing')!.value == "GRADUATING"){
      year_standing_string = 'GRADUATING';
    }

    let prerequisitestring = year_standing_string;
    if(this.courses.length > 0) {
      this.courses.controls.forEach((e) => {
        let coursecode = e.get('coursecode')?.value;
        if (prerequisitestring.length != 0){
          prerequisitestring += " & ";
        }
        prerequisitestring += coursecode;
      })
    };

    this.selectedForm!.get('prerequisites')?.patchValue(prerequisitestring);
    this.setCurriculumSubject.emit(this.selectedForm!);
    this.collapseModalEvent();
  }

  ngOnInit(){
    if(this.selectedForm!.get('prerequisites')?.value){
      let prereqs = this.selectedForm!.get('prerequisites')?.value.split(" & ");
      console.log(prereqs);
      prereqs.forEach((e:any) => {
        if (e.includes("STANDING") || e.includes("GRADUATING")){
          console.log('here');
          if (e == "1st YEAR STANDING") {
            this.preRequisite.get('year-standing')?.patchValue("1");
          }else if (e == "2nd YEAR STANDING") {
            this.preRequisite.get('year-standing')?.patchValue("2");
          }else if (e == "3rd YEAR STANDING") {
            this.preRequisite.get('year-standing')?.patchValue("3");
          }else if (e == "4th YEAR STANDING") {
            this.preRequisite.get('year-standing')?.patchValue("4");
          }else if (e == "GRADUATING"){
            this.preRequisite.get('year-standing')?.patchValue("GRADUATING");
          }

        }else{
          const subject: any = this.fb.group({
            'coursecode': new FormControl(e, [Validators.required]),
          });

          this.fac.addControl(this.courses, subject);
        }
      });
    }
  }
}
