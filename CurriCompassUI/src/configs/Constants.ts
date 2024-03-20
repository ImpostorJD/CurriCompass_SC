import { HttpHeaders } from "@angular/common/http";
import { FormGroup } from "@angular/forms";

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
  return new HttpHeaders({
  'Authorization': `Bearer ${authToken}`,
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

export const yearLevel = (a:string, b:string) => {
  const numericPartA = parseInt(a.replace(/\D/g, ''), 10);
  const numericPartB = parseInt(b.replace(/\D/g, ''), 10);

  if (!isNaN(numericPartA) && !isNaN(numericPartB)) {
    return numericPartA - numericPartB;
  } else {
    return a.localeCompare(b, undefined, { sensitivity: 'base' });
  }

}


export const sortSemester = (a: string | undefined, b: string | undefined) => {
  const semesterNumberA = parseInt((a?.match(/\d+/) ?? [""])[0], 10);
  const semesterNumberB = parseInt((b?.match(/\d+/) ?? [""])[0], 10);

  if (!isNaN(semesterNumberA) && !isNaN(semesterNumberB)) {
    return semesterNumberA - semesterNumberB;
  } else {
    return a?.localeCompare(b!, undefined, { sensitivity: 'base' });
  }
};
