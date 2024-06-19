// import { ValidatorFn, AbstractControl, ValidationErrors, FormGroup } from '@angular/forms';

// export function allOrNoneValidator(fields: string[], excludeKey: string): ValidatorFn {
//   return (control: AbstractControl): ValidationErrors | null => {
//     if (!(control instanceof FormGroup)) {
//       return null; // If it's not a FormGroup, there's no way to validate this rule
//     }

//     const formGroup = control as FormGroup;

//     // Filter out the excluded key from the fields
//     const validFields = fields.filter(field => field !== excludeKey);

//     // Perform the validation on the filtered fields
//     const allFilled = validFields.every(field => !!formGroup.get(field)?.value);
//     const allEmpty = validFields.every(field => !formGroup.get(field)?.value);

//     if (allFilled || allEmpty) {
//       return null; // valid
//     } else {
//       return { allOrNone: true }; // invalid
//     }
//   };
// }

import { ValidatorFn, AbstractControl, ValidationErrors, FormGroup } from '@angular/forms';

export function allOrNoneValidator(fields: string[], excludeField: string): ValidatorFn {
  return (control: AbstractControl): ValidationErrors | null => {
    if (!(control instanceof FormGroup)) {
      return null; // handle the case where the control is not a FormGroup
    }

    const formGroup = control as FormGroup; // cast to FormGroup

    const controls = fields.filter(field => field !== excludeField);
    const allFilled = controls.every(field => !!formGroup.get(field)?.value);
    const allEmpty = controls.every(field => !formGroup.get(field)?.value);

    if (allFilled || allEmpty) {
      // Clear custom errors on controls
      controls.forEach(field => formGroup.get(field)?.setErrors(null, { emitEvent: true }));
      return null; // valid
    } else {
      // Set custom errors on each control
      controls.forEach(field => {
        const control = formGroup.get(field);
        if (control && !control.value) {
          control.setErrors({ allOrNone: true }, { emitEvent: true });
        } else {
          control!.setErrors(null, { emitEvent: true });
        }
      });
      return { allOrNone: true }; // invalid
    }
  };
}

