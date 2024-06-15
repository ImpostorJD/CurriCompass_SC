import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'studentFilter',
  standalone: true
})
export class StudentFilterPipe implements PipeTransform {

  transform(value: any[], search: string = ''): any[] {
    if (!search) {
      return value;
    }

    search = search.toLowerCase();
    return value.filter(item => {
      const userFirstName = (item as any).userfname?.toLowerCase() || '';
      const userMiddleName = (item as any).usermiddle?.toLowerCase() || '';
      const userLastName = (item as any).userlname?.toLowerCase() || '';

      const studentNo = (item as any).student_record?.student_no?.toLowerCase() || '';
      const status = (item as any).student_record?.status?.toLowerCase() || '';
      const programdesc = (item as any).student_record?.curriculum?.program?.programdesc?.toLowerCase() || '';
      const programcode = (item as any).student_record?.curriculum?.program?.programcode?.toLowerCase() || '';
      const specialization = (item as any).student_record?.curriculum?.specialization?.toLowerCase() || '';
      const combinedString = userFirstName +" " + userLastName + " "+ userMiddleName+ " " + studentNo + " " + status + " " + programdesc + " " + programcode + " " + specialization;

      return combinedString.includes(search);
    });
  }

}
