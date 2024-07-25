import { AbstractControl, ValidatorFn } from '@angular/forms';

export function passwordValidator(): ValidatorFn {
  return (control: AbstractControl): { [key: string]: any } | null => {
    const value = control.value;

    if (!value) {
      return null;
    }

    const hasNumber = /[0-9]/.test(value);
    const hasUpperCase = /[A-Z]/.test(value);
    const hasSpecialCharacter = /[!@#$%^&*(),.?":{}|<>]/.test(value);
    const minLength = value.length >= 8;

    const passwordValid = hasNumber && hasUpperCase && hasSpecialCharacter && minLength;

    return passwordValid ? null : { 'passwordStrength': true };
  };
}
