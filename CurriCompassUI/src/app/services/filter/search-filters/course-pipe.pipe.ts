import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'coursePipe',
  standalone: true
})
export class CourseFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      const courseCodeLower = (item as any).coursecode?.toLowerCase() || '';
      const subjectDescLower = (item as any).coursedescription?.toLowerCase() || '';
      const combinedString = courseCodeLower + ' ' + subjectDescLower;

      return combinedString.includes(search);
    });
  }
}
