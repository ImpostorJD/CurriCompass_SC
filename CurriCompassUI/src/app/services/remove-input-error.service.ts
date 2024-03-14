import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class RemoveInputErrorService {

  constructor() { }

  removeError(control: any, error: any): void {
    if(control.touched && control.invalid){

      control.setErrors(error);
    }
  }
}
