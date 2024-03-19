import { Injectable } from '@angular/core';
import { FormArray, FormControl, FormGroup } from '@angular/forms';

@Injectable({
  providedIn: 'root'
})
export class FormArrayControlUtilsService {
  constructor() { }

  getFormControl(index: number, formArray: FormArray<any>, controlName: string): FormControl{
    return (formArray.at(index) as FormGroup).get(controlName)! as FormControl;
  }

  addControl(formArray:FormArray<any>, formGroup: FormGroup): void{
    formArray.push(formGroup);
  }

  popControl(formArray:FormArray<any>, index: number): void{
    formArray.removeAt(index);
  }

  clearControls(formArray:FormArray<any>): void{
    formArray.clear();
  }
}
