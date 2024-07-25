import { AbstractControl, ValidationErrors } from '@angular/forms';

export function limitValidator(control: AbstractControl): ValidationErrors | null {
  const limit = control.value;
  if (limit <= 0) {
    return { invalidLimit: true };
  }
  return null;
}
