import { AbstractControl, ValidatorFn } from '@angular/forms';

export function idValidator(): ValidatorFn {
  // Define the regex pattern for the specific format (211-1111)
  const regex = /^[0-9]{3}-[0-9]{4}$/;

  return (control: AbstractControl): { [key: string]: any } | null => {
    const valid = regex.test(control.value);
    return valid ? null : { 'invalidId': true };
  };
}
