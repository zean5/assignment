import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/react';
import { dashboard } from '@/routes';


export default function Dashboard() {
    const form = useForm({ student_id: "", name: "", course: "", year_level: "", email: "" });

    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form.post('/students', {
            onSuccess: () => form.reset(),
    }); 
    }
    
    return (
    <>
        <Head title="Dashboard" />
        <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <div>
                <h1 className="text-x1 font-semibold">Students</h1>
                    <p className="text-sm text-muted-foreground">
                        Add a student below to record.
                    </p>

                <form onSubmit= {submit} className="max-w-x1 space-y-2 rounded-x1 border p-4">
                    
                    <div className="space-y-2">
                    <label htmlFor="student_id">Student ID</label>
                    <Input 
                        id="student_id" 
                        value={form.data.student_id} 
                        onChange={(event)=> form.setData('student_id', event.target.value)}
                        placeholder="School ID Number"
                        />
                        {form.errors.student_id && <p className="text-sm text-red-600">{form.errors.student_id}</p>} 
                    </div>


                    <div className="space-y-2">
                    <label htmlFor="name">Full Name</label>
                    <Input 
                        id="name" 
                        value={form.data.name} 
                        onChange={(event)=> form.setData('name', event.target.value)}
                        placeholder="Sak Tan C. Dan"
                        />
                        {form.errors.name && <p className="text-sm text-red-600">{form.errors.name}</p>}
                    </div>
                    <div className="space-y-2">



                        
                    </div>
                    <div className="space-y-2">
                    <label htmlFor="course">Full Course Name</label>
                    <Input 
                        id="course" 
                        value={form.data.course} 
                        onChange={(event)=> form.setData('course', event.target.value)}
                        placeholder="Bachelor of Science in Information Technology"
                        />
                        {form.errors.course && <p className="text-sm text-red-600">{form.errors.course}</p>} 
                    </div>



                    <div className="space-y-2">
                    <label htmlFor="year_level">Year Level</label>
                    <Input 
                        id="year_level" 
                        value={form.data.year_level} 
                        onChange={(event)=> form.setData('year_level', event.target.value)}
                        placeholder="1st Year"
                        />
                    {form.errors.year_level && <p className="text-sm text-red-600">{form.errors.year_level}</p>}
                    
                    </div>


                    
                    <div className="space-y-2">
                    <label htmlFor="email">Email</label>
                    <Input  
                        id="email" 
                        value={form.data.email} 
                        onChange={(event)=> form.setData('email', event.target.value)}
                        placeholder="School email address"
                        />
                        {form.errors.email && <p className="text-sm text-red-600">{form.errors.email}</p>}  
                    </div>
                    <Button type="submit" disabled={form.processing}>Save Student</Button>
                </form>



            </div>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            {/* <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
                <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
                <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
            <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
            </div> */}
        </div>
    </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
