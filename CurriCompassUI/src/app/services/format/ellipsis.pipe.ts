import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'ellipsis',
  standalone: true
})
export class EllipsisPipe implements PipeTransform {

  /**
   * March 3/21/24
   *
   * To transform string and limit character, followed by ellipsis to ensure continuity.
   * @param value
   * @param limit
   *
   * @returns string
   */
  transform(value: string, limit: number): any {
    if(limit && value.length > limit) {
      return value.substring(0, limit).concat('...');
    }
    return value;
  }
}
