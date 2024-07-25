import { Component, EventEmitter, Input, Output } from '@angular/core';
import { HttpReqHandlerService } from '../../services/http-req-handler.service';
import { AuthService } from '../../services/auth/auth.service';
import { FormArray, FormBuilder, FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { httpOptions, markFormGroupAsDirtyAndInvalid } from '../../../configs/Constants';
import { FormArrayControlUtilsService } from '../../services/form-array-control-utils.service';
import { CommonModule } from '@angular/common';
import { PreRequisiteModalComponent } from '../pre-requisite-modal/pre-requisite-modal.component';

@Component({
  selector: 'app-curriculum-course-table',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule, PreRequisiteModalComponent],
  templateUrl: './curriculum-course-table.component.html',
  styleUrl: './curriculum-course-table.component.css'
})
export class CurriculumCourseTableComponent {
  @Output() collapseModal = new EventEmitter<boolean>();
  @Output() setCurriculumSubject = new EventEmitter<FormGroup>();
  @Input('selectedForm') selectedForm:FormGroup<any> | null = null;
  @Input('selection') selection:FormArray<any> | null = null;

  constructor(private fb: FormBuilder, private fac: FormArrayControlUtilsService, private req: HttpReqHandlerService, private auth: AuthService) {}

  prerequisiteindex = -1;
  prerequisiteform: FormGroup | null = null;

  collapseModalEvent() {
    this.collapseModal.emit(true);
  }

  popsubjectsArray(index: number) {
    this.fac.popControl(this.subjectsFormArray, index);
  }

  get subjectsFormArray() {
    return this.selectedForm!.get('subjects') as FormArray;
  }

  get subjectsFormControl() {
    return this.selectedForm!.get('subjects') as FormControl;
  }

  getCourseCodeControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'coursecode');
  }

  getCourseDescriptionControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'coursedescription');
  }

  getPrerequisitesControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'prerequisites');
  }

  getUnitsControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'units');
  }

  getUnitsLabControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'unitslab');
  }

  getUnitsLecControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'unitslec');
  }

  getHoursLabControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'hourslab');
  }

  getHoursLecControl(index: number): FormControl {
    return this.fac.getFormControl(index, this.subjectsFormArray, 'hourslec');
  }

  addSubject(){
    const subject: any = this.fb.group({
      'coursecode': new FormControl('', [Validators.required]),
      'coursedescription': new FormControl('', [Validators.required]),
      'prerequisites': new FormControl(''),
      'units': new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
      'unitslab': new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
      'unitslec': new FormControl('', [Validators.required, Validators.pattern("^[0-9]*$")]),
      'hourslab': new FormControl('', [Validators.required, Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
      'hourslec': new FormControl('', [Validators.required, Validators.pattern("^[0-9]+(\.?[0-9]+)?")]),
    });

    this.fac.addControl(this.subjectsFormArray, subject);
  }

  addPrerequisite(index: number){
    this.prerequisiteindex = index;
    this.prerequisiteform = this.subjectsFormArray.at(index) as FormGroup;
  }

  updateForm(event:any){
    console.log(event.value);
  }

  handleCodeChange(index: number) {
    let found = false;
    let val = this.getCourseCodeControl(index).value;

    this.selection?.controls.forEach((control, outerIndex) => {
      let innerControl = control.get('subjects') as FormArray;
      innerControl.controls.forEach((innerControl, innerIndex) => {
        if (index !== innerIndex) {
          let subject = innerControl.get('coursecode')?.value;
          if (val === subject) {
            found = true;
          }
        }
      });
    });

    if (found) {
      this.getCourseCodeControl(index).setErrors({ 'existing': true });
    } else {
      this.getCourseCodeControl(index).setErrors(null);
    }
  }

  handleDescriptionChange(index: number) {
    let found = false;
    let val = this.getCourseDescriptionControl(index).value;

    this.selection?.controls.forEach((control, outerIndex) => {
      let innerControl = control.get('subjects') as FormArray;
      innerControl.controls.forEach((innerControl, innerIndex) => {
        if (index !== innerIndex) {
          let subject = innerControl.get('coursedescription')?.value;
          if (val === subject) {
            found = true;
          }
        }
      });
    });

    if (found) {
      this.getCourseDescriptionControl(index).setErrors({ 'existing': true });
    } else {
      this.getCourseDescriptionControl(index).setErrors(null);
    }
  }


  onSubmit() {
    if(this.selectedForm!.status == "INVALID") {
      markFormGroupAsDirtyAndInvalid(this.selectedForm!);
      return;
    }

    this.setCurriculumSubject.emit(this.selectedForm!);
    this.collapseModalEvent();
  }
}
