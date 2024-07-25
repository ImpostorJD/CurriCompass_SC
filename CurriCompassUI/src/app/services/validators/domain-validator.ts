import { AbstractControl, ValidatorFn } from '@angular/forms';

export function emailDomainValidator(): ValidatorFn {
  // Define the regex pattern for the specific domain
  const regex = /^[a-zA-Z0-9._%+-]+@baliuagu\.edu\.ph$/;

  return (control: AbstractControl): { [key: string]: any } | null => {
    const valid = regex.test(control.value);
    return valid ? null : { 'emailDomain': { value: control.value } };
  };
}

