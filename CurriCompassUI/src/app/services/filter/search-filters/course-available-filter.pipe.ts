import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'courseAvailableFilter',
  standalone: true
})
export class CourseAvailableFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      if (typeof item !== 'object') {
        return item.toLowerCase().includes(search);
      }
      const courseCodeLower = (item as any).coursecode?.toLowerCase() || '';
      const subjectDescLower = (item as any).coursedescription?.toLowerCase() || '';
      const combinedString = courseCodeLower + ' ' + subjectDescLower;

      return combinedString.includes(search);
    });
  }

}
