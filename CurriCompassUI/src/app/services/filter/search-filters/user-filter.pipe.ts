import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'userFilter',
  standalone: true
})
export class UserFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      const userFirstName = (item as any).userfname?.toLowerCase() || '';
      const userMiddleName = (item as any).usermiddle?.toLowerCase() || '';
      const userLastName = (item as any).userlname?.toLowerCase() || '';
      const combinedString = userFirstName +" " + userLastName + " "+ userMiddleName;

      return combinedString.includes(search);
    });
  }

}
