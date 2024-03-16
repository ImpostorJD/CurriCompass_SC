import { Injectable } from '@angular/core';

/**
 * 3/14/2024
 *
 * USAGE: Inject to component to access.
 * PURPOSE: This is used to remove error messages from each from field, assuming that you are using reactive forms module.
 */
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
