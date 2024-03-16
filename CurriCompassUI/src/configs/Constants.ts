import { HttpHeaders } from "@angular/common/http";
import { FormArray, FormGroup } from "@angular/forms";

/**
 * 3/1/2024
 *
 * Constants file
 * This is mostly used to configure authorization header, this is the default setting and can still be overridden
 *
 * @author John Daniel Tejero
 * @param authToken
 */
export const httpOptions = (authToken: string) => {
  headers: new HttpHeaders({
  //'Authorization': `Bearer ${authToken}`,
  'Content-Type': 'application/json',
  'accept': 'application/json',
})}

export function markFormGroupAsDirtyAndInvalid(formGroup: FormGroup) {
  Object.values(formGroup.controls).forEach(control => {
    if (control instanceof FormGroup) {
      markFormGroupAsDirtyAndInvalid(control);
    }else {
      control.markAsTouched();
      control.markAsDirty();
      control.markAsTouched();
    }
  });
}
